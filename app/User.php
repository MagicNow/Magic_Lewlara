<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['first_name', 'last_name', 'email', 'username', 'password', 'active'];


	public function getDates()
	{
	    return ['created_at','updated_at'];
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
     * If provided password is empty, use the old one instead
     *
     * @param string $password 
     */
	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = empty($password)
		    ? $this->password
		    : bcrypt($password);
	}

	


	public function is_admin(){
		if($this->group()->first()->id == 1 || $this->group()->first()->id == 4){  // usar contains() ?
			return true;
		} else {
			return false;
		}
	}

	public function is_usuario(){
		if($this->group()->first()->id == 2){  // usar contains() ?
			return true;
		} else {
			return false;
		}
	}

	public function is_cliente(){
		if($this->group()->first()->id == 3){  // usar contains() ?
			return true;
		} else {
			return false;
		}
	}



	/**
	 * Relação Many To Many
	 */

    public function group()
    { 
        return $this->belongsToMany('App\Group');
    }  

	/**
	 * Relação Many To Many
	 */

    public function cliente()
    { 
        return $this->belongsToMany('App\Cliente')->orderBy('name','asc');
    }  


    // get list of clientes id associated with the user

    public function getClienteListAttribute(){
    	return $this->cliente->lists('id');
    }

    // query scope scopes

    public function scopeClienteSlug($query,$slug)
    {
        return $query->whereHas('cliente', function($query) use ($slug){
						    $query->where('slug', $slug);
						});
    }

    /**
     * Relação One To Many
     */
    public function post()
    { 
        return $this->hasMany('App\Post');
    } 

    /**
     * Relação One To Many
     */
    public function newsletter()
    { 
        return $this->hasMany('App\Newsletter');
    } 

	/**
	 * Relação One To Many
	 */
	public function comentarios()
	{ 
	    return $this->hasMany('App\Comentario');
	} 

	/**
	 * Get all of the postsfavoritos from user.
	 */
	public function postsfavoritosbkp()
	{
	    return $this->hasMany('App\PostFavorito');
	}

	public function postsfavoritos()
	{
	    return $this->belongsToMany('App\Post','posts_favoritos');
	}

}