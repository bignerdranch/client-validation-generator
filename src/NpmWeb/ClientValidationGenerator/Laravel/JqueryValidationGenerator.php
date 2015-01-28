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

        // fill in container arrays first, to handle nonstandard field validations
        $mappedRules = array();
        foreach( $allRules as $field => $fieldRules ) {
            $mappedRules[$field] = array();
        }

        foreach( $allRules as $field => $fieldRules ) {
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
                $fieldToSet = $field;
                if( !is_array($ruleMapping) ) {
                    $mappedRuleName = $ruleMapping;
                    $mappedParam = $param;
                } else { // is array
                    $mappedRuleName = $ruleMapping['rule'];
                    $mappedParam = $ruleMapping['param']($param, $field);
                    if( isset($ruleMapping['fieldOverride']) ) {
                        $fieldToSet = $ruleMapping['fieldOverride']($param, $field);
                    }
                }
                if( null == $mappedParam ) {
                    $mappedParam = true;
                }

                // add to jQuery array
                $mappedRules[$fieldToSet][$mappedRuleName] = $mappedParam;
            }
            if (array_key_exists('minlength', $mappedRules[$field])) {
                if (array_key_exists('number', $mappedRules[$field])) {
                    $mappedRules[$field]['min'] = $mappedRules[$field]['minlength'];
                    unset($mappedRules[$field]['minlength']);
                }
            }
            if (array_key_exists('maxlength', $mappedRules[$field])) {
                if (array_key_exists('number', $mappedRules[$field])) {
                    $mappedRules[$field]['max'] = $mappedRules[$field]['maxlength'];
                    unset($mappedRules[$field]['maxlength']);
                }
            }
        }

        return $mappedRules;
    }

}
