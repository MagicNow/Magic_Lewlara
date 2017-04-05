<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'configs';
        public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	//protected $fillable = ['name', 'link', 'slug', 'logo', 'active'];




 
	/**
	 * Relação Many To Many
	 */

}

