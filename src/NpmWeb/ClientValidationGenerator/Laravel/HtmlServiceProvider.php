<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

class HtmlServiceProvider extends \Collective\Html\HtmlServiceProvider {

	/**
	 * Register the form builder instance.
	 *
	 * @return void
	 */
	protected function registerFormBuilder()
	{
		$this->app->bindShared('form', function($app)
		{
			$form = $this->createFormBuilder($app);
			$form->setClientValidationGenerator( $this->createClientValidationGenerator($app) );
			return $form->setSessionStore($app['session.store']);
		});
	}

	protected function createFormBuilder($app) {
		return new FormBuilder( $app['html'], $app['url'], $app['session.store']->getToken());
	}

	/**
	 * Sets up the client validation generator instance
	 */
	protected function createClientValidationGenerator($app) {
		return $app->make('client-validation');
	}

}