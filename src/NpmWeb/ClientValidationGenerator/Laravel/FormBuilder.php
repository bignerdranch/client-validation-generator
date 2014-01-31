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

	var $gen;

	public function __construct( Generator $gen ) {
		$this->gen = $gen;
	}

	public function model($model, array $options = array())
	{
		$extra = '';

		// if validation requestsd
		if( array_key_exists('validate',$options) && $options['validate'] ) {
			// make sure form has an ID
			if( array_key_exists('id',$options) ) {
				$formId = $options['id'];
			} else {
				$class = str_replace('\\', '_', get_class($model));
				$formId = 'form_'.$class;
				$options['id'] = $formId;
			}

			// get validator code
			$extra = $this->gen->generateClientValidatorCode( $model::$rules, $formId );
		}

		// return results
		$results = parent::model($model, $options);
		return $results . $extra;
	}

}
