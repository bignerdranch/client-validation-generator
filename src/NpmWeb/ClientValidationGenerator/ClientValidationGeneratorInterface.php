<?php

namespace NpmWeb\ClientValidationGenerator;

interface ClientValidationGeneratorInterface {

    const START = 1;
    const END = 2;

    /**
     * Returns client validator code for the given model class. Any
     * JavaScript should be wrapped in <script> tags. It will be
     * included immediately after the opening <form> tag.
     */
    public function generateClientValidatorCode( $rules, $formId, $extraValidationRules = null );

    /**
     * Returns the position the validation code should be placed at, START or
     * END.
     */
    public function getCodePosition();
}
