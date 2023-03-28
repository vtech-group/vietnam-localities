<?php

namespace Vtech\VietnamLocalities\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The Province Model class.
 *
 * @package vtech/vietnam-localities
 * @author  Jackie Do <anhvudo@gmail.com>
 */
class Province extends Model
{
    /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'provinces';

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
    ];

    /**
     * Get the districts belong to this province.
     */
    public function districts()
    {
        return $this->hasMany(District::class,  'province_id');
    }
}
