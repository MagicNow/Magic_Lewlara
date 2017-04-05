<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PostHistorico extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts_historico';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['titulo','slug', 'desc','publicar_em','status', 'cliente_id','destaque','post_id','users_id','created_at','updated_at'];
	
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
	
	public function cliente()
    { 
        return $this->belongsTo('App\Cliente');
    } 

    /**
     * Relação Many To Many
     */
    public function tag()
    { 
        return $this->belongsToMany('App\Tag');
    }
    
    public function midia()
    { 
        return $this->belongsToMany('App\Midia');
    } 

    /**
     * Relação Many To Many
     */
    public function concorrente()
    { 
        return $this->belongsToMany('App\Concorrente');
    } 

    // set subcategoria to null if blank
    public function setsubcategoriaIdAttribute($valor){
       $this->attributes['subcategoria_id'] = empty($valor) ? null : $valor;
    }

    // get list of tag id associated with the post
    public function getTagListAttribute(){
        return $this->tag->lists('id');
    }

    // get list of tiposAcao id associated with the post
    public function getTiposAcaoListAttribute(){
        return $this->tiposAcao->lists('id');
    }

    /**
     * Relação Many To Many
     */
    public function categoria()
    { 
        return $this->belongsToMany('App\Categoria','post_historico_categoria_sub_categoria','post_historico_id','categoria_id')->withPivot('sub_categoria_id');
    } 

    /**
     * Relação Many To Many
     */
    public function tiposAcao()
    { 
        return $this->belongsToMany('App\TiposAcao');
    } 


    // query scope scopes

    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }

    
    

}

    