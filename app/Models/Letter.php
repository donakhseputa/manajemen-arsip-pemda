<?php

namespace App\Models;

use App\Enums\LetterType;
use App\Enums\Config as ConfigEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{
    use HasFactory,
        SoftDeletes;

    public const STATUS_BELUM_DIPROSES = 'belum_diproses';
    public const STATUS_DISPOSISI = 'disposisi';
    public const STATUS_SUDAH_DIBALAS = 'sudah_dibalas';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_BATAL = 'batal';

    public const STATUS_OPTIONS = [
        self::STATUS_BELUM_DIPROSES,
        self::STATUS_DISPOSISI,
        self::STATUS_SUDAH_DIBALAS,
        self::STATUS_SELESAI,
        self::STATUS_BATAL,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'reference_number',
        'agenda_number',
        'from',
        'to',
        'letter_date',
        'received_date',
        'description',
        'note',
        'type',
        'status',
        'is_read',
        'classification_code',
        'user_id',
        'year',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'letter_date' => 'date',
        'received_date' => 'date',
        'year' => 'integer',
        'is_read' => 'boolean',
    ];

    protected $appends = [
        'formatted_letter_date',
        'formatted_received_date',
        'formatted_created_at',
        'formatted_updated_at',
    ];

    public function getFormattedLetterDateAttribute(): string {
        return Carbon::parse($this->letter_date)->isoFormat('dddd, D MMMM YYYY');
    }

    public function getFormattedReceivedDateAttribute(): string {
        return Carbon::parse($this->received_date)->isoFormat('dddd, D MMMM YYYY');
    }

    public function getFormattedCreatedAtAttribute(): string {
        return $this->created_at->isoFormat('dddd, D MMMM YYYY, HH:mm:ss');
    }

    public function getFormattedUpdatedAtAttribute(): string {
        return $this->updated_at->isoFormat('dddd, D MMMM YYYY, HH:mm:ss');
    }

    public function scopeType($query, LetterType $type)
    {
        return $query->where('type', $type->type());
    }

    public function scopeIncoming($query)
    {
        return $this->scopeType($query, LetterType::INCOMING);
    }

    public function scopeOutgoing($query)
    {
        return $this->scopeType($query, LetterType::OUTGOING);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now());
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeYesterday($query)
    {
        return $query->whereDate('created_at', now()->addDays(-1));
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function($query, $find) {
            return $query
                ->where('reference_number', $find)
                ->orWhere('agenda_number', $find)
                ->orWhere('from', 'LIKE', $find . '%')
                ->orWhere('to', 'LIKE', $find . '%');
        });
    }

    public function scopeRender($query, $search, $perPage = null)
    {
        $perPage = $perPage ?? Config::getValueByCode(ConfigEnum::PAGE_SIZE);

        return $query
            ->with(['attachments', 'classification'])
            ->search($search)
            ->latest('created_at')
            ->paginate($perPage)
            ->appends([
                'search' => $search,
            ]);
    }

    public function scopeAgenda($query, $since, $until, $filter)
    {
        return $query
            ->when($since && $until && $filter, function ($query, $condition) use ($since, $until, $filter) {
                return $query->whereBetween(DB::raw('DATE(' . $filter . ')'), [$since, $until]);
            });
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class, 'classification_code', 'code');
    }

    /**
     * @return HasMany
     */
    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class, 'letter_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'letter_id', 'id');
    }
}
