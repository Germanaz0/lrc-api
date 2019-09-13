<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * Service gelocation model
 *
 * Class Service
 * @package App\Models
 */
class Service extends Model
{
    use SpatialTrait;

    protected $fillable = [
        'title',
        'description',
        'address',
        'city',
        'state',
        'zip_code',
        'geolocation',
    ];

    protected $spatialFields = [
        'geolocation',
    ];

    /**
     * Short alias of the scope to filter by distance but allowing kilometers.
     * @param $center
     * @param $kmInput
     * @return mixed
     */
    public function scopeKmDistance($query, $center, $kmInput) {
        return $this->scopeDistanceSphere($query,'geolocation', $center, $kmInput * 1000);
    }
}
