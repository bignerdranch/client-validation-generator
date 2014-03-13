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

	protected $useRequireJs;

	public function __construct( $useRequireJs ) {
		$this->useRequireJs = $useRequireJs;
	}

	/**
	 * Generates a script tag with the jQuery Validator binding.
	 * Delegates to the abstract generateClientValidatorRules() to
	 * translate from server-side rules to jQuery rules.
	 */
	public function generateClientValidatorCode( $allRules, $formId ) {
		$mappedRules = $this->generateClientValidatorRules( $allRules );
		$html = '<script type="text/javascript">';
		if( $this->useRequireJs ) {
			$html .= 'require([\'jquery-validation\'],function(a){';
		} else {
			$html .= '$(function(){';
		}
		$html .= '$("#'. $formId.'").validate({ rules: '.json_encode($mappedRules).'});'
				. '});'
				. '</script>';

		return $html;
	}

	/**
	 * Takes source rules in some format and translates them into jQuery
	 * Validator rules.
	 *
	 * @param $sourceRules rules in the format a server-side validator uses
	 * @return a PHP array structure of rules in the format jQuery uses, i.e.
	 *
	 * array( 'myfield' => array( 'required' => true, 'maxlength' => 20 ) )
	 */
	protected abstract function generateClientValidatorRules( $sourceRules );

}
