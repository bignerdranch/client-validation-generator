<?php

namespace NpmWeb\CommunityService\ServiceProviders;

use NpmWeb\ClientValidationGenerator\Laravel\JqueryValidationGenerator;

class FoundationHtmlServiceProvider 
	extends HtmlServiceProvider
{

	/**
	 * Sets up the client validation generator instance
	 */
	protected function createClientValidationGenerator() {
		return new JqueryValidationGenerator(
			true,
			'jquery-validation-foundation',
			'validate_foundation'
		);
	}

}
