<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'unit',
        'healthy_min',
        'healthy_max',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'healthy_min' => 'decimal:2',
        'healthy_max' => 'decimal:2',
    ];

    /**
     * Get the user marker values for this marker.
     */
    public function userMarkerValues(): HasMany
    {
        return $this->hasMany(UserMarkerValue::class);
    }
}