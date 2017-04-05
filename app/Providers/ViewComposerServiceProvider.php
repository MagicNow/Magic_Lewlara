<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// retorna notifications_count
		view()->composer('topo_nav', '\App\Http\Composers\TopoNavComposer@compose');
	}

	public function register()
	{
		
	}

}
