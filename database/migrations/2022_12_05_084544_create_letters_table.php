<?php

use App\Models\ArchiveClassification;
use App\Models\Letter;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $archiveClassification;
    protected string $letter;
    protected string $user;

    public function __construct()
    {
        $this->archiveClassification = (new ArchiveClassification())->getTable();
        $this->letter = (new Letter())->getTable();
        $this->user = (new User())->getTable();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->letter, function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->comment('Nomor Surat');
            $table->string('agenda_number');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->date('letter_date')->nullable();
            $table->date('received_date')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->year('year')->nullable();
            $table->string('type')->default('incoming')->comment('Surat Masuk (incoming)/Surat Keluar (outgoing)');
            $table->string('classification_code');
            $table->foreignId('user_id')->constrained($this->user)->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->letter);
    }
};
