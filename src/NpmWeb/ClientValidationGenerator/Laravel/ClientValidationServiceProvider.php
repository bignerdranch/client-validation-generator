<?php namespace NpmWeb\ClientValidationGenerator\Laravel;

use Illuminate\Support\ServiceProvider;

class ClientValidationServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// @see https://coderwall.com/p/svocrg
		$this->package('npmweb/client-validation-generator', null, __DIR__.'/../../../');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('client-validation', function($app)
		{
			// Once the authentication service has actually been requested by the developer
			// we will set a variable in the application indicating such. This helps us
			// know that we need to set any queued cookies in the after event later.
			$app['client-validation.loaded'] = true;

			return (new ClientValidationManager($app))->driver();
		});
	}
}
