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
}
