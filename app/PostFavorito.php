<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PostFavorito extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts_favoritos';
        public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	

    /**
     * Relação One To Many
     */

    public function post()
    { 
        return $this->belongsTo('App\Post','post_id');
    } 
    
    public function midia()
    { 
        return $this->belongsToMany('App\Midia');
    } 

    public function postsMaisFavoritados()
    {
    	dd($cliente);
    }

    // query scope scopes

    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }
 
    
    
}

    