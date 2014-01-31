<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

use NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface as Generator;

/**
 * Form that makes it easy to insert the client validator code if you
 * are using Ardent to put validation rules in the model itself.
 */
class Form extends \Illuminate\Html\FormBuilder {

	var $gen;

	public function __construct( Generator $gen ) {
		$this->gen = $gen;
	}

	public function model( $modelInstance, $options )
	{
		$extra = '';

		// if validation requestsd
		if( array_key_exists('validate',$options) && $options['validate'] ) {
			// make sure form has an ID
			if( array_key_exists('id',$options) ) {
				$formId = $options['id'];
			} else {
				$class = str_replace('\\', '_', get_class($modelInstance));
				$formId = 'form_'.$class;
				$options['id'] = $formId;
			}

			// get validator code
			$extra = $this->gen->generateClientValidatorCode( $modelInstance->rules, $formId );
		}

		// return results
		$results = parent::model($modelInstance, $options);
		return $results . $extra;
	}

}
