<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Newslettergroup extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'newsletter_group';
    public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	
	protected $fillable = ['name'];

 	
	/**
	 * Relação Many To Many
	 */

    public function pessoas()
    { 
        return $this->belongsToMany('App\User','newsletter_group_user','newsletter_group_id','user_id');
    }  
    

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
    public function newsletters()
    { 
        return $this->belongsToMany('App\Newsletter','newsletter_newsletter_group','newsletter_group_id','newsletter_id');
    } 


    // query scope scopes

    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }

    public function newsletterPessoas($newsletter_id)
    { 
        $pessoas_id = \DB::table('newsletter_user')->where('newsletter_group_id', $this->id)->where('newsletter_id', $newsletter_id)->lists('user_id');

        $pessoas = new \Illuminate\Database\Eloquent\Collection;

        foreach ($pessoas_id as $pessoa_id) {
        	$pessoa = User::find($pessoa_id);
        	if(count($pessoa)){
        		$pessoas->add($pessoa);	
        	}        	
        }

        return $pessoas;
    } 
}

