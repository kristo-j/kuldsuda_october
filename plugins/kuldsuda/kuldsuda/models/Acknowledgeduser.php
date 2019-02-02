<?php namespace Kuldsuda\Kuldsuda\Models;

use Model;

/**
 * Acknowledgeduser Model
 */
class Acknowledgeduser extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'kuldsuda_kuldsuda_acknowledgedusers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
