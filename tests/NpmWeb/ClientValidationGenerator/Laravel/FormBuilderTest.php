<?php

namespace NpmWeb\ClientValidationGenerator\Laravel;

use Mockery;

class FormBuilderTest extends \NpmWeb\Test\TestCase {
	
	var $gen;
	var $form;

	public function __construct() {
	}

	public function setUp() {
		$this->gen = Mockery::mock('NpmWeb\ClientValidationGenerator\ClientValidationGeneratorInterface');
		$this->form = Mockery::mock('NpmWeb\ClientValidationGenerator\Laravel\FormBuilder',array($this->gen))->makePartial();
	}

	public function testModelWithValidation() {
		// arrange
		$formId = 'myform';
		$modelInstance = new MyModel();
		$scriptTag = '<script>This is validator code!</script>';
		$this->gen
			->shouldReceive('generateClientValidatorCode')
			->with( $modelInstance::$rules, $formId )
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
		$modelInstance = new MyModel();
		$this->form
			->shouldReceive('open')
			->andReturn('<form>');

		// act
		$output = $this->form->model( $modelInstance, array( 'id'=>$formId, 'validate'=>false ) );

		// assert
		$this->assertEquals( '<form>', $output );
	}
	
}

class MyModel {
	public static $rules = array(
		'name' => array('required','max:20'),
	);
}
