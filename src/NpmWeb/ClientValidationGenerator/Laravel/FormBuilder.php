<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

use NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface as Generator;

/**
 * FormBuilder that makes it easy to insert the client validator code if
 * you are using Ardent to put validation rules in the model itself.
 *
 * To use this FormBuilder, open config/app.php, find the 'providers'
 * array, and replace
 *   'Illuminate\Html\HtmlServiceProvider',
 * with
 *   'NpmWeb\ClientValidationGenerator\Laravel\HtmlServiceProvider',
 */
class FormBuilder extends \Illuminate\Html\FormBuilder {

    private $gen;
    private $npmValidate;
    private $npmModel;
    private $npmFormId;

    public function setClientValidationGenerator( Generator $gen ) {
        $this->gen = $gen;
    }

    public function model($model, array $options = array())
    {
        // set up form ID
        if( array_key_exists('validate',$options) && $options['validate'] ) {
            $this->npmValidate = true;

            // make sure form has an ID
            if( array_key_exists('id',$options) ) {
                $this->npmFormId = $options['id'];
            } else {
                $class = str_replace('\\', '_', get_class($model));
                $this->npmFormId = 'form_'.$class;
                $options['id'] = $this->npmFormId;
            }

            // save extra rules
            if( array_key_exists('extra-validation-rules', $options) ) {
                $this->extraValidationRules = $options['extra-validation-rules'];
                unset($options['extra-validation-rules']);
            }
        }

        // pass to parent
        $this->npmModel = $model;
        $results = parent::model($model, $options);
        if( Generator::START == $this->gen->getCodePosition() ) {
            $results .= $this->generateClientValidatorCode();
        }
        return $results;
    }

    public function close()
    {
        $results = '';
        if( Generator::END == $this->gen->getCodePosition() ) {
            $results .= $this->generateClientValidatorCode();
        }
        $results .= parent::close();
        return $results;
    }

    protected function generateClientValidatorCode()
    {
        $extra = '';

        // if validation requestsd
        if( $this->npmValidate ) {
            $model = $this->npmModel;
            if( $model ) {
                $extra = $this->gen->generateClientValidatorCode( $model::$rules, $this->npmFormId, $this->extraValidationRules );
            }
        }

        return $extra;
    }

}
