<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

/**
 * Form that makes it easy to insert the client validator code if you
 * are using Ardent to put validation rules in the model itself.
 */
class Form extends \Illuminate\Support\Facades\Form {

	public static function model( $modelInstance, $options )
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
			$validator = \App::make('NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface');
			$extra = $validator->generateClientValidatorCode( $modelInstance::$rules, $formId );
		}

		// return results
		$results = parent::model($modelInstance, $options);
		return $results . $extra;
	}

}
