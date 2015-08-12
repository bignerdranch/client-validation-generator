# client-validation-generator

Server-side code to generate client-side validation, usually based on server-side validation rules from a different validation framework. Built in is code to generate jQuery Validator rules on the client-side from Laravel Validator rules on the server side.

## Installation

Add `npmweb/client-validation-generator` as a requirement to `composer.json`:

```javascript
{
    "require": {
        "npmweb/client-validation-generator": "1.*"
    }
}
```

Update your packages with `composer update`.

Comment out any existing HtmlServiceProvider in your Laravel service providers list, and add this package's instead

```php
return array(
    ...
    'providers' => array(
        ...
        // 'Illuminate\Html\HtmlServiceProvider',
        'NpmWeb\ClientValidationGenerator\Laravel\HtmlServiceProvider',
    ),
);
```

Publish the package's config file to your app using `php artisan config:publish npmweb/client-validation-generator`.

## Usage

Once it's set up, how to use it depends on what kind of model you're using:

- If you're using [Ardent](https://github.com/laravelbook/ardent), when you call Form::model() or Form::close() (depending on a setting), pass `validate="true"`, and a <script /> tag will be outputted with your generated client-side validation rules.
- If you're using some other method of validation, call Form::clientValidation() and pass in the array of validation rules, and the <script /> tag will be outputted with your generated client-side validation rules.

To configure how this works, edit the config file `app/config/packages/npmweb/client-validation-generator/config.php`. It has the following values:

- `'driver'`: what driver to use. The only built-in one is `'jquery'`
- `'useRequireJs'`: true if any required JS libraries should be loaded via require.js; false if they will be loaded some other way, like a plain <script /> tag
- `'packageName'`: when using require.js, the names of the packages to load
- `'useDocumentReady'`: whether the validation JS code should be run after the document has fully loaded, using jQuery's document ready
- `'codeAtEnd'`: whether the <script /> tag to load the validation should come at the start or end of the <form /> tag
- `'functionName'`: the name of the function to call; allows, for example, calling libraries that extend jQuery validator
- `'ruleMappings'`: specifies how server-side validation rules should be translated to client-side ones

### Rule Mappings

In the config file, `'ruleMappings'` is an associative array. The key is the server-side validation rule name, and the value specifies how to generate the client-side rule.

In the simplest case, if the value is a string, the rule is carried over exactly from server to client. This string can be the same rule name as the key/server-side or a different one.

If the value is an associative array, several keys can be set:

- `'name'`: the name of the client-side rule
- `'param'`: a function defining what the parameter to the rule should be. This function receives the server-side parameter, and it can be transformed. For example, maybe the server-side rule is 'same' with a param of 'otherField', and the client-side rule needs to be 'equalTo' with a param of 'input[name=otherField]'. This could be accomplished with the following:

```php
    'param' => function( $param ) {
        return 'input[name=' . $param . ']';
    },
```

-`'fieldOverride`: this lets you override the field the rule is set on. For example, maybe you have an email field and an email_confirmation field. On the server side, maybe the rule exists on the email field, but on the client-side you want the rule to exist on the email_confirmation field. You could accomplish that with the following:

```php
    'fieldOverride' => function( $param, $field ) {
        return $field.'_confirmation';
    },
```

## License

This code is open-sourced under the MIT license. For more information,
see the LICENSE file.
