<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMarkerValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_test_entry_id',
        'marker_id',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
    ];

    /**
     * Get the test entry that this value belongs to.
     */
    public function userTestEntry(): BelongsTo
    {
        return $this->belongsTo(UserTestEntry::class);
    }

    /**
     * Get the marker details for this value.
     */
    public function marker(): BelongsTo
    {
        return $this->belongsTo(Marker::class);
    }
}