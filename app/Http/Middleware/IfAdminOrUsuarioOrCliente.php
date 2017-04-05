<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class IfAdminOrUsuarioOrCliente {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if (!$this->auth->user() || !($this->auth->user()->is_admin() || $this->auth->user()->is_usuario() || $this->auth->user()->is_cliente()))
		{		
				return redirect(\URL::to('/'));	
				return response('Unauthorized.', 401);			
		}

		return $next($request);
	}

}
