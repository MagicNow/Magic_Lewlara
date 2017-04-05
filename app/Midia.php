<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Midia extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'midias';
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
    
    public function post()
    { 
        return $this->belongsToMany('App\Post');
    }  
    
    public function post_historico()
    { 
        return $this->belongsToMany('App\PostHistorico');
    } 
    public function post_favorito()
    { 
        return $this->belongsToMany('App\PostFavorito');
    } 

}

