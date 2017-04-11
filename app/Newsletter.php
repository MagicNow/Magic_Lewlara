<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'newsletter';
    public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	
	protected $fillable = ['assunto'];

 
	
	/**
	 * Relação One To Many
	 */
    public function cliente()
    { 
        return $this->belongsTo('App\Cliente');
    } 

	/**
	 * Relação Many To Many
	 */

    public function post()
    { 
        return $this->belongsToMany('App\Post')->withPivot('atualizacao');
    }  

	/**
	 * Relação Many To Many
	 */
    public function groups()
    { 
        return $this->belongsToMany('App\Newslettergroup','newsletter_newsletter_group','newsletter_id','newsletter_group_id');
    } 

    /**
     * Relação One To Many
     */
    public function autor() // autor
    { 
        return $this->belongsTo('App\User','user_id');
    } 

	/**
	 * Relação Many To Many
	 */

    public function pessoa()
    { 
        return $this->belongsToMany('App\User')->withPivot('newsletter_group_id');
    }  

    // query scope scopes

    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }

}

