<?php

return array(
	'driver' => 'jquery',

	'useRequireJs' => true,
	'packageName' => ['jquery-validation'],
	'functionName' => 'validate',

	'ruleMappings' => array(
		'required' => 'required',
		'min' => 'minlength',
		'max' => 'maxlength',
		'date' => 'date',
		'email' => 'email',
		'url' => 'url',
		'numeric' => 'number',
		'before_now' => 'before_now',
		'same' => array(
			'rule' => 'equalTo',
			'param' => function( $param ) {
				return 'input[name=' . $param . ']';
			},
		),
	),

);
