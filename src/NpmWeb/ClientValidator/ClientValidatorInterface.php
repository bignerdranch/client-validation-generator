<?php

namespace NpmWeb\ClientValidator;

interface ClientValidatorInterface {

	/**
	 * Returns client validator code for the given model class. Any
	 * JavaScript should be wrapped in <script> tags. It will be
	 * included immediately after the opening <form> tag.
	 */
	public function generateClientValidatorCode( $rules, $formId );
}
