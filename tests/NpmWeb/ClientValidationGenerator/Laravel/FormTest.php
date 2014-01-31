<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

use Mockery;

class FormTest extends \NpmWeb\Test\TestCase {
	
	var $gen;
	var $form;

	public function __construct() {
	}

	public function setUp() {
		$this->gen = Mockery::mock('NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface');
		$this->form = Mockery::mock('NpmWeb\ClientValidationGenerator\Laravel\Form',array($this->gen))->makePartial();
	}

	public function testModelWithValidation() {
		// arrange
		$formId = 'myform';
		$rules = array( 'foo' => true, 'bar' => 20 );
		$modelInstance = (object)array(
			'rules' => $rules
		);
		$scriptTag = '<script>This is validator code!</script>';
		$this->gen
			->shouldReceive('generateClientValidatorCode')
			->with( $rules, $formId )
			->andReturn($scriptTag);
		$this->form
			->shouldReceive('open')
			->andReturn('<form>');

		// act
		$output = $this->form->model( $modelInstance, array( 'id'=>$formId, 'validate'=>true ) );

		// assert
		$this->assertEquals( '<form>'.$scriptTag, $output );
	}
	
	public function testModelWithoutValidation() {
		// arrange
		$formId = 'myform';
		$modelInstance = (object)array();
		$this->form
			->shouldReceive('open')
			->andReturn('<form>');

		// act
		$output = $this->form->model( $modelInstance, array( 'id'=>$formId, 'validate'=>false ) );

		// assert
		$this->assertEquals( '<form>', $output );
	}
	
}