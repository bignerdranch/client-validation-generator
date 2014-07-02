<?php namespace NpmWeb\ClientValidationGenerator\Laravel;

use Illuminate\Support\Manager;

class ClientValidationManager extends Manager {

	static $configFile = 'client-validation';

	/**
	 * Create a new driver instance.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	protected function createDriver($driver)
	{
		$clientValidationGenerator = parent::createDriver($driver);

		// any other setup needed

		return $clientValidationGenerator;
	}

	/**
	 * Create an instance of the database driver.
	 *
	 * @return \Illuminate\Auth\Guard
	 */
	public function createJqueryDriver()
	{
		return new JqueryValidationGenerator(
			$this->app['config']->get(self::$configFile.'.ruleMappings'),
			$this->app['config']->get(self::$configFile.'.useRequireJs'),
			$this->app['config']->get(self::$configFile.'.packageName'),
			$this->app['config']->get(self::$configFile.'.functionName')
		);
	}

	/**
	 * Get the default authentication driver name.
	 *
	 * @return string
	 */
	public function getDefaultDriver()
	{
		return $this->app['config'][self::$configFile.'.driver'];
	}

	/**
	 * Set the default authentication driver name.
	 *
	 * @param  string  $name
	 * @return void
	 */
	public function setDefaultDriver($name)
	{
		$this->app['config'][self::$configFile.'.driver'] = $name;
	}

}
