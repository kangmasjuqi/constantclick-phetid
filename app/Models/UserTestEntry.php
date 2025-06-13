<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserTestEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'test_panel_id',
        'test_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'test_date' => 'date',
    ];

    /**
     * Get the user that owns the test entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the test panel associated with the test entry.
     */
    public function testPanel(): BelongsTo
    {
        return $this->belongsTo(TestPanel::class);
    }

    /**
     * Get the marker values for the test entry.
     */
    public function userMarkerValues(): HasMany
    {
        return $this->hasMany(UserMarkerValue::class);
    }
}