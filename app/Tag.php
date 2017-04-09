<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tags';
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

    public function cliente()
    { 
        return $this->belongsTo('App\Cliente');
    } 

	/**
	 * Relação Many To Many
	 */

    public function post()
    { 
        return $this->belongsToMany('App\Post');
    }  

     public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }
   

}

