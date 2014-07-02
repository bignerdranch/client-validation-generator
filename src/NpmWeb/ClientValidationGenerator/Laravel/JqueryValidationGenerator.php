<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

/**
 * Generates jQuery validation from Laravel server-side rules.
 */
class JqueryValidationGenerator
	extends \NpmWeb\ClientValidationGenerator\BaseJqueryValidationGenerator
{

	protected $ruleMappings;

	public function __construct(
		$ruleMappings,
		$useRequireJs,
		$packageName = array('jquery-validation'),
		$functionName = 'validate'
	) {
		parent::__construct($useRequireJs,$packageName,$functionName);
		$this->ruleMappings = $ruleMappings;
	}

	protected function generateClientValidatorRules( $allRules ) {
		$jQueryValidatorRuleMappings = $this->ruleMappings;

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
