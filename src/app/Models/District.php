<?php

namespace Vtech\VietnamLocalities\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The Province Model class.
 *
 * @package vtech/vietnam-localities
 * @author  Jackie Do <anhvudo@gmail.com>
 */
class District extends Model
{
    /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'districts';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
    * The attributes that are mass assignable.
    *
    * @var mixed
    */
    protected $fillable = [
        'id',
        'name',
        'province_id',
    ];

    /**
     * Get the province that owns the district.
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Get all wards for this district.
     */
    public function districts()
    {
        return $this->hasMany(Ward::class, 'district_id');
    }
}
