<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Calculate affiliate distance from latitude and longitude of a radius sphere
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $radius
     * @return void
     */
    public function distance(float $latitude, float $longitude)
    {
        return $this->distance = great_circle_distance($this->latitude, $this->longitude, $latitude, $longitude);
    }
}
