<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
		'ifAdmin' => 'App\Http\Middleware\IfAdmin',
		'ifAdminOrUsuario' => 'App\Http\Middleware\IfAdminOrUsuario',
		'ifAdminOrUsuarioOrCliente' => 'App\Http\Middleware\IfAdminOrUsuarioOrCliente',
		'ifUsuario' => 'App\Http\Middleware\IfUsuario',
		'ifCliente' => 'App\Http\Middleware\IfCliente'
	];

}
