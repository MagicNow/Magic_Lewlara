<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categorias';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'slug','order', 'cliente_id'];


	/**
     * If provided password is empty, use the old one instead
     *
     * @param string $password 
     */
	public function setSlugAttribute($value)
	{
		$this->attributes['slug'] = str_slug($value);
	}

	
	public function cliente()
    { 
        return $this->belongsTo('App\Cliente');
    } 

    /**
     * Relação One To Many
     */
    public function subcategoria()
    { 
        return $this->hasMany('App\Subcategoria')->orderBy('order', 'asc')->orderBy('name', 'asc');
    } 
    
    /**
     * Relação Many To Many
     */
    public function post()
    { 
        return $this->belongsToMany('App\Post','post_categoria_sub_categoria','categoria_id','post_id')->withPivot('sub_categoria_id');
    } 
    
        // query scope scopes

        public function scopeClienteSlug($query,$slug)
        {
            return $query->whereHas('cliente', function($query) use ($slug){
    						    $query->where('slug', $slug);
    						});
        }

}

    