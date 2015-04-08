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
        'confirmed' => array(
            'rule' => 'equalTo',
            'param' => function( $param, $field ) {
                return '#'.$field;
            },
            'fieldOverride' => function( $param, $field ) {
                // set it on the confirmation field, not the main one
                return $field.'_confirmation';
            },
        ),

        // if you're using the jQuery Additional validators, you can uncomment the following
        'integer' => 'integer',
    ),

);
