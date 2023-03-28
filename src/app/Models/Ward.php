<?php

namespace Vtech\VietnamLocalities\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The Province Model class.
 *
 * @package vtech/vietnam-localities
 * @author  Jackie Do <anhvudo@gmail.com>
 */
class Ward extends Model
{
    /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'wards';

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
        'district_id'
    ];

    /**
     * Get the district that owns the ward.
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
