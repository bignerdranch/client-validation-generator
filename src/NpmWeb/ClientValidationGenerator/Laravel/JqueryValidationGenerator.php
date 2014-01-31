<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

/**
 * Generates jQuery validation from Laravel server-side rules.
 */
class JqueryValidationGenerator
	extends \NpmWeb\ClientValidationGenerator\BaseJqueryValidationGenerator
{

	protected static function jQueryValidatorRuleMappings() {
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

	protected function generateClientValidatorRules( $allRules ) {
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

		return $mappedRules;
	}

}
