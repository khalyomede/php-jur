# php-jur
PHP class that follows the JSON Uniform Response standard

[![GitHub tag](https://img.shields.io/github/tag/khalyomede/php-jur.svg)]()
![PHP minimum required version](https://img.shields.io/badge/php-%3E%3D5.3.0-777BB4.svg)

## Why should I use this library
JUR (JSON Uniform Response) is as its title says a consistent way to deliver resource through JSON response. It helps a lot with debugging and maintaining the API. For more information about this standard, you can access [the documentation](https://github.com/khalyomede/jur). This library is the implementation of these guidelines.

## JUR implemented version
PHP-JUR implements version **1.1** of this standard.

## Installation
This project needs [Composer](https://getcomposer.org/) to be installed. If you prefer not using this dependency manager, go to the section [Installation without composer](#installation-without-composer).
In your project folder root, open a command prompt and enter :
```bash
composer require khalyomede/php-jur
```
In any file you whish to use this library, you need to include the autoloader line if it is not done. For example, if you are runing this script in a file located at the root of your project :
```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Khalyomede\JUR;

$response = JUR::reset()
  ->update()
  ->message('John Doe have successfuly been saved')
  ->success()
  ->resolved()
  ->toJson();
  
// ...
```
However, if you run this script in a folder named "users" for example, you will need to update the path to the autoloader in consequence :
```php
<?php

require __DIR__ . '/../vendor/autoload.php'; // beware, this path has changed

use Khalyomede\JUR;

$response = JUR::reset()
  ->update()
  ->message('John Doe have successfuly been saved')
  ->success()
  ->resolved()
  ->toJson();
  
// ...
```

## Installation without composer
You can copy the entire content of the file located at `src/jur.php`. Remove the line `namespace Khalyomede;` and include it wherever you need. You can then follow the example of usage to use this library.

## Examples
- [Example of usage 1 : sending a success message after a creation](#example-of-usage-1--sending-a-success-message-after-a-creation)
- [Example of usage 2 : sending an error message after an update](#example-of-usage-2--sending-an-error-message-after-an-update)
- [Example of usage 3 : sending a fail error while getting a resource](#example-of-usage-3--sending-a-fail-error-while-getting-a-resource)
- [Example of usage 4 : getting response to array](#example-of-usage-4--getting-response-to-array)
- [Example of usage 5 : getting response to object](#example-of-usage-5--getting-response-to-object)
- [Example of usage 6 : getting response to JSON](#example-of-usage-6--getting-response-to-json)

## Example of usage 1 : sending a success message after a creation
```php
JUR::reset()->requested();

$user = createUser(); // hypothetical function that make an insertion and return the created resource

$response = JUR::post()
  ->message('John Doe have been successfuly created')
  ->data( $user )
  ->success()
  ->resolved()
  ->toJson();
```
[back to the example list](#examples)
## Example of usage 2 : sending an error message after an update
```php
$response = JUR::reset()
  ->requested()
  ->update();

$pdo = null;

try {
  $pdo = getPdo();
  
  $user = updateUser( $pdo ); // hypothetical function, can throw an Exception
  
  $response = JUR::message('John Doe have successfuly been updated')
    ->data( $user )
    ->success();
}
catch( Exception $e ) {
  $response = JUR::message('an error occured while updating John Doe')
    ->code( $pdo->errorInfo()[1] )
    ->error();
}

$response = $response->resolved()->toJson();
```
[back to the example list](#examples)
## Example of usage 3 : sending a fail error while getting a resource
```php
const ERROR_EMAIL_INCORRECT_MSG = 'this email is incorrectly formed';
const ERROR_EMAIL_INCORRECT_CODE = -1:

$response = JUR::reset()
  ->requested()
  ->get();

try {
  $email = filter_var( $_GET['email'], FILTER_SANITIZE_EMAIL );

  if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
    throw new InvalidArgumentException( ERROR_EMAIL_INCORRECT_MSG, ERROR_EMAIL_INCORRECT_CODE );
  }
  
  $users = getUsersByEmail( $email );
  
  $response = JUR::data( $users )
    ->success();
}
catch( InvalidArgumentException $e ) {
  $response = JUR::message( $e->getMessage() )
    ->code( $e->getCode() )
    ->fail();
}
catch( Exception $e ) {
  $response = JUR:message('an error occured while trying to fetch users')
    ->code( $pdo->errorInfo()[1] )
    ->error();
}

$response = $response->resolved()->toJson();
```
[back to the example list](#examples)
## Example of usage 4 : getting response to array
```php
JUR::reset()->requested();

$user = getUser(); // hypothetical function

$response = JUR::get()
  ->data( $user )
  ->success()
  ->resolved()
  ->toArray();
```

## Example of usage 5 : getting response to object
**Warning**

This function can be slow if the data is huge because of the function that convert an array to an object (using json_encode + json_decode technique).
```php
JUR::reset()->requested();

$user = getUser(); // hypothetical function

$response = JUR::get()
  ->data ( $user )
  ->success()
  ->resolved()
  ->toObject();
```
[back to the example list](#examples)
## Example of usage 6 : getting response to JSON
```php
JUR::reset()->requested();

$user = getUser(); // hypothetical function

$response = JUR::get()
  ->data ( $user )
  ->success()
  ->resolved()
  ->toJson();
```

[back to the example list](#examples)
## Semantic Version ready

This library follows the [semantic versioning guidelines v2.0.0](http://semver.org/) that ensure every step we engage, by adding new functionalities or providing bug fixes, follows these guide and make your job easier by trusting this library as versioningly stable. We strongly encourage using always the last version of the major version number, as it always provides the latest security patches. If you would like to go from a major version to another, an effort will be done to help users passing from one to another.
