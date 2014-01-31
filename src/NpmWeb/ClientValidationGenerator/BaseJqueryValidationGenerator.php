<?php

namespace NpmWeb\ClientValidationGenerator;

/**
 * Abstract class for a jQuery validation generator. Defines how to
 * output jQuery validation rules; subclasses must implement how to get
 * those rules.
 *
 * This class does not include the jQuery and jQuery Validation JS files
 * in your page--do that separately.
 */
abstract class BaseJqueryValidationGenerator
	implements \NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface
{

	public function generateClientValidatorCode( $allRules, $formId ) {
		$mappedRules = $this->generateClientValidatorRules( $allRules );
		return '<script type="text/javascript">$(function(){ $("#'. $formId.'").validate({ rules: '.json_encode($mappedRules).'}); })</script>';
	}

	protected abstract function generateClientValidatorRules( $sourceRules );

}
