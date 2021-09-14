<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    const HEART_RADIUS = 6371000;

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
     * Return the latitude in radian
     *
     * @return void
     */
    public function getLatitudeInRadiantAttribute()
    {
        return deg2rad($this->latitude);
    }

    /**
     * Return the longitude in radian
     *
     * @return void
     */
    public function getLongitudeInRadiantAttribute()
    {
        return deg2rad($this->longitude);
    }

    /**
     * Calculate affiliate distance from latitude and longitude of a radius sphere
     *
     * @param float $lat
     * @param float $lng
     * @param int $radius
     * @return void
     */
    public function distance($lat, $lng, $radius = self::HEART_RADIUS)
    {        
        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $radius;
    }
}
