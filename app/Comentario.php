<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model {

	protected $table = 'comentarios';

	protected $fillable = ['user_id', 'post_id', 'comentario'];

	
	public function post()
    { 
        return $this->belongsTo('App\Post');
    } 

    public function user()
    { 
        return $this->belongsTo('App\User');
    } 
    
    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('post', function($query) use ($slug){
        				$query->ClienteSlug($slug);
				});
    }
}

    