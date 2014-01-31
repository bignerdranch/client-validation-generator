<?php

namespace NpmWeb\ClientValidationGenerator;

interface ClientValidationGeneratorInterface {

	/**
	 * Returns client validator code for the given model class. Any
	 * JavaScript should be wrapped in <script> tags. It will be
	 * included immediately after the opening <form> tag.
	 */
	public function generateClientValidatorCode( $rules, $formId );
}
