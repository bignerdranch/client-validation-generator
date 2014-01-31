<?php

namespace NpmWeb\ClientValidator\Laravel;

class JqueryClientValidatorTest extends \NpmWeb\Test\TestCase {
	
	var $rules;
	var $validator;

	public function __construct() {
		$this->rules = array(
			'mystring' => array(
				'required',
				'min:3',
				'max:20',
				'same:mystring_confirm'
			),
			'mydate' => array(
				'required',
				'date',
			),
			'myemail' => array(
				'required',
				'email',
			),
			'myurl' => array(
				'url',
			),
			'mynum' => array(
				'numeric',
				'max:6',
			)
		);
	}

	public function setUp() {
		$this->validator = new JqueryClientValidator();
	}

	public function testGenerateClientValidatorRules() {
		// arrange
		$method = $this->getMethod( 'NpmWeb\ClientValidator\Laravel\JqueryClientValidator', 'generateClientValidatorRules' );

		// act
		$clientRules = $method->invokeArgs( $this->validator, array($this->rules));

		// assert
		$this->assertEquals(
			array(
				'mystring' => array(
					'required' => true,
					'minlength' => '3',
					'maxlength' => '20',
					'equalTo' => 'input[name=mystring_confirm]',
				),
				'mydate' => array(
					'required' => true,
					'date' => true,
				),
				'myemail' => array(
					'required' => true,
					'email' => true,
				),
				'myurl' => array(
					'url' => true,
				),
				'mynum' => array(
					'number' => true,
					'maxlength' => 6,
				)
			),
			$clientRules,
			'resulting client rules were not as expected'
		);
	}
	
}