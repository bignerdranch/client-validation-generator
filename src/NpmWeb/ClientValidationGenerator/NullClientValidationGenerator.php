<?php namespace NpmWeb\ClientValidationGenerator;

class NullClientValidationGenerator implements ClientValidationGeneratorInterface {

  public function generateClientValidatorCode( $rules, $formId, array $options = null ) {
    return "";
  }

  public function getCodePosition() {
    return START;
  }
}
