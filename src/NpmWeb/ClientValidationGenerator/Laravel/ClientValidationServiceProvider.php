<?php namespace NpmWeb\ClientValidationGenerator\Laravel;

use Illuminate\Support\ServiceProvider;

class ClientValidationServiceProvider extends ServiceProvider {

    protected $configFilePath;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->configFilePath = __DIR__.'/../../../config/config.php';
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([ $this->configFilePath => config_path('client-validation.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( $this->configFilePath, 'client-validation' );
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
