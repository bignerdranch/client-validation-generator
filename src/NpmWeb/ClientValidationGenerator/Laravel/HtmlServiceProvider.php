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
			$form = new FormBuilder( $app['html'], $app['url'], $app['session.store']->getToken());

			$form->setClientValidationGenerator( \App::make(
				'NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface') );

			return $form->setSessionStore($app['session.store']);
		});
	}

}