<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

class FoundationHtmlServiceProvider 
	extends HtmlServiceProvider
{

	/**
	 * Sets up the client validation generator instance
	 */
	protected function createClientValidationGenerator() {
		return new JqueryValidationGenerator(
			true,
			['jquery-validation-foundation','jquery-validator-custom-rules'],
			'validate_foundation'
		);
	}

}
