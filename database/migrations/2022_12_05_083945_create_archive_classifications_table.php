<?php

use App\Models\ArchiveClassification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $archiveClassification;

    public function __construct()
    {
        $this->archiveClassification = (new ArchiveClassification())->getTable();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->archiveClassification, function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('parent_id')->index()->nullable()->constrained($this->archiveClassification)->cascadeOnDelete();
            $table->string('code')->index();
            $table->string('full_code', 255)->index()->unique();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('level')->index()->default(1);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists($this->archiveClassification);
    }
};
