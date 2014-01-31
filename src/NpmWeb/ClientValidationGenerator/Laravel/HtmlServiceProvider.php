<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

/**
 * Registers the client-validation enabled FormBuilder as the default
 */
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
			$form = new Form( \App::make(
				'NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface'),
				$app['html'], $app['url'], $app['session.store']->getToken());

			return $form->setSessionStore($app['session.store']);
		});
	}

}
