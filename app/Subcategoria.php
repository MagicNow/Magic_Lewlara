<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sub_categorias';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'slug','order', 'categoria_id'];


	public function getNameAttribute($value)
    {
        return $this->attributes['name'];
    }

	/**
     * Define atributo slug a partir do nome enviado
     *
     */
	public function setNameAttribute($value)
	{
		$this->attributes['name'] = $value;
		$this->attributes['slug'] = str_slug($value);
	}

	/**
	 * Relação Many To Many
	 */
	public function post()
	{ 
	    return $this->belongsToMany('App\Post','post_categoria_sub_categoria','sub_categoria_id','post_id')->withPivot('categoria_id');
	} 
	
	public function categoria()
    { 
        return $this->belongsTo('App\Categoria');
    } 

    // query scope scopes

    public function scopeCategoriaId($query,$categoria_id)
    {
        return $query->whereHas('categoria', function($query) use ($slug){
						    $query->where('id', $categoria_id);
						});
    }

}

    