<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['titulo', 'desc', 'cliente_id','destaque','status'];
	
	public function scopePostsAtivos()
    {
        return $this->where('status',2)->where('publicar_em', '<=', date('Y-m-d H:i:s'));
    }

    // if post is active
    public function postAtivo(){
        if($this->status == 2 && strtotime($this->publicar_em) <= strtotime(date('Y-m-d H:i:s'))){
            return true;
        }
        return false;
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

    /**
     * Relação Many To Many
     */
    public function newsletter()
    { 
        return $this->belongsToMany('App\Newsletter');
    } 

    /**
     * Relação Many To Many
     */
    public function concorrente()
    { 
        return $this->belongsToMany('App\Concorrente');
    } 

    /**
     * Relação Many To Many
     */
    public function midia()
    { 
        return $this->belongsToMany('App\Midia');
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

    // get subcategoria name
    public function subcategoriaName($id){
        if($sub = Subcategoria::find($id)){
            return $sub->name;
        } 
        return false;
    }

    // get list of tag id associated with the post
    public function getStatusNameAttribute(){
        switch ($this->status) {
            case '1':
                return 'Rascunho';
                break;
            case '2':
                if(strtotime($this->publicar_em) > strtotime(date('Y-m-d H:i:s'))){
                    return 'Agendado';
                } else {
                    return 'Publicado';
                }
                break;
        }
    }

    /**
     * Relação Many To Many
     */
	public function categoria()
    { 
        return $this->belongsToMany('App\Categoria','post_categoria_sub_categoria','post_id','categoria_id')->withPivot('sub_categoria_id');
    } 

    /**
     * Relação Many To Many
     */
    public function subcategoria()
    { 
        return $this->belongsToMany('App\Subcategoria','post_categoria_sub_categoria','post_id','sub_categoria_id')->withPivot('categoria_id');
    } 

    /**
     * Relação One To Many
     */
    public function user()
    { 
        return $this->belongsTo('App\User');
    } 

    /**
     * Relação Many To Many
     */
    public function tiposAcao()
    { 
        return $this->belongsToMany('App\TiposAcao','post_tipos_acao');
    } 

    /**
     * Relação One To Many
     */
    public function posthistorico()
    { 
        return $this->hasMany('App\PostHistorico');
    } 

    /**
     * Relação One To Many
     */
    public function comentario()
    { 
        return $this->hasMany('App\Comentario');
    } 

    /**
     * RelaÃƒÂ§ÃƒÂ£o One To Many
     */
    public function postfavorito()
    { 
        return $this->hasMany('App\PostFavorito');
    }


    /**
     * retorna quantas vezes um post foi favoritado
     */
    public function postfavoritocount()
    { 
        return $this->postfavorito->count();
    }

    public function userfavoritou()
    {
        return $this->belongsToMany('App\User','posts_favoritos');
    }

    // query scope scopes

    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }


    public function scopeCategoriaId($query,$cat_id)
    {
        return $query->whereHas('categoria', function($query) use ($cat_id){
                            $query->where('categoria_id', $cat_id);
                        });
    }

    public function scopeSubCategoriaId($query,$cat_id)
    {
        return $query->whereHas('subcategoria', function($query) use ($cat_id){
                            $query->where('sub_categoria_id', $cat_id);
                        });
    }

    public function scopeTagId($query,$tag_id)
    {
        return $query->whereHas('tag', function($query) use ($tag_id){
                            $query->where('tag_id', $tag_id);
                        });
    }

    public function scopeCategoriaSlug($query,$slug)
    {
        return $query->whereHas('categoria', function($query) use ($slug){
                            $query->where('slug', $slug);
                        });
    }
    
    public function scopeSubcategoriaSlug($query,$slug)
    {
        return $query->whereHas('subcategoria', function($query) use ($slug){
                            $query->where('slug', $slug);
                        });
    }
    
}

    