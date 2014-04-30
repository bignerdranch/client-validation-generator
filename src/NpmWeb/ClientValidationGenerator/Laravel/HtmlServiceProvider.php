<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

class HtmlServiceProvider extends \Illuminate\Html\HtmlServiceProvider {

	/**
	 * Register the form builder instance.
	 *
	 * @return void
	 */
	protected function registerFormBuilder()
	{
		$this->app->bindShared('form', function($app)
		{
			$form = $this->createFormBuilder();
			$form->setClientValidationGenerator( $this->createClientValidationGenerator() );
			return $form->setSessionStore($app['session.store']);
		});
	}

	protected function createFormBuilder() {
		return new FormBuilder( $app['html'], $app['url'], $app['session.store']->getToken());
	}

	/**
	 * Sets up the client validation generator instance
	 */
	protected function createClientValidationGenerator() {
		return new JqueryValidationGenerator(true);
	}

}