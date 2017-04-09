<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'clientes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'link', 'slug', 'logo', 'active'];
 
	/**
	 * Relação Many To Many
	 */

    public function user()
    { 
        return $this->belongsToMany('App\User');
    } 

    /**
     * Relação One To Many
     */
    public function tag()
    { 
        return $this->hasMany('App\Tag');
    }

    /**
     * Relação One To Many
     */
    public function post()
    { 
        return $this->hasMany('App\Post')->orderBy('created_at','desc');
    }

    /**
     * Relação One To Many
     */
    public function newsletter()
    { 
        return $this->hasMany('App\Newsletter');
    } 

    /**
     * Relação One To Many
     */
    public function tiposAcao()
    { 
        return $this->hasMany('App\TiposAcao');
    } 

    /**
     * Relação One To Many
     */
    public function concorrente()
    { 
        return $this->hasMany('App\Concorrente');
    } 

    /**
     * Relação One To Many
     */
    public function categoria()
    { 
        return $this->hasMany('App\Categoria');
    } 

    /**
     * Get all of the postsfavoritos for the cliente.
     */
    public function postsfavoritos()
    {
        return $this->hasManyThrough('App\PostFavorito', 'App\Post');
    }

    public function postsmaisfavoritos()
    {
        return $this->hasManyThrough('App\PostFavorito', 'App\Post')->groupBy('post_id')->orderBy('count','DESC')->get(array('post_id', \DB::raw('count(*) as count')));
    }

}

