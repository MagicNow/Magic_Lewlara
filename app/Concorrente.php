<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Concorrente extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'concorrentes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'link', 'logo', 'cliente_id'];

	
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

    /**
     * Relação Many To Many
     */
    public function post()
    { 
        return $this->belongsToMany('App\Post');
    } 

}

    