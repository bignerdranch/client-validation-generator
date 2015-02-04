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
    protected $useDocumentReady;
    protected $codeAtEnd;
    protected $packageName;
    protected $functionName;

    public function __construct(
        $packageName = array('jquery-validation'),
        $functionName = 'validate',
        $options = array()
    ) {
        $this->packageName = $packageName;
        $this->functionName = $functionName;
        $this->useRequireJs = $options['useRequireJs'];
        $this->useDocumentReady = $options['useDocumentReady'];
        $this->codeAtEnd = $options['codeAtEnd'];
    }

    /**
     * Returns the position the validation code should be placed at, START or
     * END.
     */
    public function getCodePosition() {
        return $this->codeAtEnd ? self::END : self::START;
    }

    /**
     * Generates a script tag with the jQuery Validator binding.
     * Delegates to the abstract generateClientValidatorRules() to
     * translate from server-side rules to jQuery rules.
     */
    public function generateClientValidatorCode( $allRules, $formId ) {
        $mappedRules = $this->generateClientValidatorRules( $allRules );
        $js = '$("#'. $formId.'").'.$this->functionName.'({ rules: '.json_encode($mappedRules)
                . '});';

        // wrap
        if( $this->useRequireJs ) {
            if( is_array($this->packageName) ) {
                $packages = $this->packageName;
            } else {
                $packages = array($this->packageName);
            }
            $js = 'require('.json_encode($packages).', function(a){'
                . $js
                . '});';
        } elseif( $this->useDocumentReady ) {
            $js .= '$(function(){'.$js.'});';
        } else {
            // leave $js as is
        }

        return '<script type="text/javascript">'.$js."</script>";
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
