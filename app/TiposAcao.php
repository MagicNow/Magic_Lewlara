<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposAcao extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tipos_acao';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'icon', 'cliente_id'];

	
	/**
	 * Relação Many To Many
	 */
	public function post()
	{ 
	    return $this->belongsToMany('App\Post','post_tipos_acao');
	} 

	public function cliente()
    { 
        return $this->belongsTo('App\Cliente');
    } 

    // query scope scopes

    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }

}

    