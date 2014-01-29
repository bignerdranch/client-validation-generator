<?php

namespace NpmWeb\ClientValidator;

/**
 * A client validator that uses jQuery Validation. This script does not
 * include the jQuery and jQuery Validation JS files in your page--do
 * that separately.
 */
class JqueryClientValidator implements ClientValidatorInterface {

	public static function jQueryValidatorRuleMappings() {
		return array(
			'required' => 'required',
			'min' => 'minlength',
			'max' => 'maxlength',
			'date' => 'date',
			'email' => 'email',
			'url' => 'url',
			'numeric' => 'number',
			'same' => array(
				'rule' => 'equalTo',
				'param' => function( $param ) {
					return 'input[name=' . $param . ']';
				},
			),
		);
	}

	public function generateClientValidatorCode( $allRules, $formId ) {
		$jQueryValidatorRuleMappings = self::jQueryValidatorRuleMappings();

		$mappedRules = array();
		foreach( $allRules as $field => $fieldRules ) {

			$mappedRules[$field] = array();

			if( !is_array($fieldRules) ) {
				$fieldRules = explode('|',$fieldRules);
			}
			foreach( $fieldRules as $rule ) {
				// extract rule data
				if( FALSE === strpos( $rule, ':' ) ) {
					$ruleName = $rule;
					$param = null;
				} else {
					$parts = explode(':', $rule);
					$ruleName = $parts[0];
					$param = $parts[1];
				}

				// find out if mapped
				if( !array_key_exists( $ruleName, $jQueryValidatorRuleMappings ) ) {
					continue;
				}

				// if mapped, get new data
				$ruleMapping = $jQueryValidatorRuleMappings[$ruleName];
				if( !is_array($ruleMapping) ) {
					$mappedRuleName = $ruleMapping;
					$mappedParam = $param;
				} else { // is array
					$mappedRuleName = $ruleMapping['rule'];
					$mappedParam = $ruleMapping['param']($param);
				}
				if( null == $mappedParam ) {
					$mappedParam = true;
				}

				// add to jQuery array
				$mappedRules[$field][$mappedRuleName] = $mappedParam;
			}
		}

		return '<script type="text/javascript">$(function(){ $("#'. $formId.'").validate({ rules: '.json_encode($mappedRules).'}); })</script>';
	}

}
