# client-validation-generator

Server-side code to generate client-side validation, usually based on server-side validation rules from a different validation framework. Current implementation generates jQuery Validator rules on the client-side from Laravel Validator rules on the server side, but this could be extended for any combination of server-side and client-side framework.

## Installation

1. Add `npmweb/client-validation-generator` as a requirement to `composer.json`:

        {
            "require": {
                "npmweb/client-validation-generator": "^2.0"
            }
        }

2. Update your packages with `composer update`.

3. If you're using Laravel, comment out any existing HtmlServiceProvider in your Laravel service providers list, and add this package's instead:

        'providers' => array(
            ...
            // LaravelCollective\Html\HtmlServiceProvider::class,
            NpmWeb\ClientValidationGenerator\Laravel\HtmlServiceProvider::class,
            NpmWeb\ClientValidationGenerator\Laravel\ClientValidationServiceProvider::class,
        ),

4. If you're using Laravel, publish the package's config file to your app:

        $ php artisan vendor:publish --provider="NpmWeb\ClientValidationGenerator\Laravel\HtmlServiceProvider"

## Usage

Once it's set up, how to use it depends on what kind of model you're using:

- If you're using [Ardent](https://github.com/laravelbook/ardent), when you call Form::model() or Form::close() (depending on a setting), pass `validate="true"`, and a \<script /> tag will be outputted with your generated client-side validation rules.
- If you're using some other method of validation, call Form::clientValidation() and pass in the array of validation rules, and the \<script /> tag will be outputted with your generated client-side validation rules.

To configure how this works, edit the config file `app/config/client-validation.php`. It has the following values:

- `'driver'`: what driver to use. The only built-in one is `'jquery'`
- `'useRequireJs'`: true if the required JS libraries should be loaded via require.js; false if they will already be loaded some other way by your application's code, like a plain \<script /> tag
- `'packageName'`: when using require.js, the names of the packages to load
- `'useDocumentReady'`: whether the validation setup JS code should be run after the document has fully loaded, using jQuery's document ready. If false, will run immediately.
- `'codeAtEnd'`: whether the \<script /> tag to load the validation should come at the start or end of the \<form /> tag. Defaults to the start.
- `'functionName'`: the name of the function to call; allows, for example, calling libraries that extend jQuery validator
- `'ruleMappings'`: specifies how server-side validation rules should be translated to client-side ones (see below for details)

### Rule Mappings

In the config file, `'ruleMappings'` is an associative array. The key is the server-side validation rule name, and the value specifies how to generate the client-side rule.

In the simplest case, if the value is a string, the rule is carried over exactly from server to client. This string can be the same rule name as the key/server-side or a different one.

If the value is an associative array, several keys can be set:

- `'name'`: the name of the client-side rule
- `'param'`: a function defining what the parameter to the rule should be. This function receives the server-side parameter, and it can be transformed. For example, maybe the server-side rule is 'same' with a param of 'otherField', and the client-side rule needs to be 'equalTo' with a param of 'input[name=otherField]'. This could be accomplished with the following:

        'param' => function( $param ) {
            return 'input[name=' . $param . ']';
        },

- `'fieldOverride`: this lets you override the field the rule is set on. For example, maybe you have an email field and an email_confirmation field. On the server side, maybe the rule exists on the email field, but on the client-side you want the rule to exist on the email_confirmation field. You could accomplish that with the following:

        'fieldOverride' => function( $param, $field ) {
            return $field.'_confirmation';
        },

## License

This code is open-sourced under the MIT license. For more information,
see the LICENSE file.
