# php-jur

Json Uniform Response for PHP.

![Packagist](https://img.shields.io/packagist/v/khalyomede/php-jur.svg)
![PHP from Packagist](https://img.shields.io/packagist/php-v/khalyomede/php-jur.svg)
![Packagist](https://img.shields.io/packagist/l/khalyomede/php-jur.svg)

## Summary

- [Standard](#standard)
- [Installation](#installation)
- [Examples](#examples)

For a support for PHP 5.X, please use the version 1.* of this library. Note the version 1.* is no longer maintained.

## Standard

Json Uniform Response (JUR) is a way to deliver JSON response in a consistant manner. To learn more, go to the [official documentation](https://github.com/khalyomede/jur).

## Installation

In your root project folder, type the following command:

```bash
composer require khalyomede/php-jur:2.*
```

## Examples

- [Get a response as a JSON string](#get-a-json-response)
- [Get a response as an array](#get-a-response-as-an-array)
- [Get a response as an object](#get-a-response-as-an-array)
- [Attach a message to the response](#attach-a-message-to-the-response)
- [Set custom timestamps](#set-custom-timestamps)

### Get a response as a JSON string

```php
require(__DIR__ . '/../vendor/autoload.php');

$response = jur()
  ->request('get')
  ->data([
    ['id' => 1, 'name' => 'New in PHP 7.2', 'author' => 'Carlo Daniele'],
    ['id' => 2, 'name' => 'Help for new PHP project', 'author' => 'Khalyomede']
  ])
  ->toJson();

echo $response;
```

```json
{"message":null,"request":"get","data":[{"id":1,"name":"New in PHP 7.2","author":"Carlo Daniele"},{"id":2,"name":"Help for new PHP project","author":"Khalyomede"}],"debug":{"elapsed":27,"issued_at":1529617930807795,"resolved_at":1529617930807822}}
```

### Get a response as an array

```php
require(__DIR__ . '/../vendor/autoload.php');

$response = jur()
  ->request('get')
  ->data([
    ['id' => 1, 'name' => 'New in PHP 7.2', 'author' => 'Carlo Daniele'],
    ['id' => 2, 'name' => 'Help for new PHP project', 'author' => 'Khalyomede']
  ])
  ->toArray();

print_r($response);
```

```php
Array
(
    [message] =>
    [request] => get
    [data] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [name] => New in PHP 7.2
                    [author] => Carlo Daniele
                )

            [1] => Array
                (
                    [id] => 2
                    [name] => Help for new PHP project
                    [author] => Khalyomede
                )

        )

    [debug] => Array
        (
            [elapsed] => 24
            [issued_at] => 1.5296179859844E+15
            [resolved_at] => 1.5296179859845E+15
        )

)
```

### Get a response as an object

```php
require(__DIR__ . '/../vendor/autoload.php');

$response = jur()
  ->request('get')
  ->data([
    ['id' => 1, 'name' => 'New in PHP 7.2', 'author' => 'Carlo Daniele'],
    ['id' => 2, 'name' => 'Help for new PHP project', 'author' => 'Khalyomede']
  ])
  ->toObject();

print_r($response);
```

```php
stdClass Object
(
    [message] =>
    [request] => get
    [data] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [name] => New in PHP 7.2
                    [author] => Carlo Daniele
                )

            [1] => Array
                (
                    [id] => 2
                    [name] => Help for new PHP project
                    [author] => Khalyomede
                )

        )

    [debug] => stdClass Object
        (
            [elapsed] => 27
            [issued_at] => 1.5296180326839E+15
            [resolved_at] => 1.5296180326839E+15
        )

)
```

### Attach a message to the response

```php
require(__DIR__ . '/../vendor/autoload.php');

$response = jur()
  ->request('post')
  ->message('Task saved.')
  ->data([
    'id' => 42
  ])
  ->toJson();

echo $response;
```

```json
{"message":"Task saved.","request":"post","data":{"id":42},"debug":{"elapsed":26,"issued_at":1529618086871988,"resolved_at":1529618086872014}}
```

### Set custom timestamps

```php
require(__DIR__ . '/../vendor/autoload.php');

$response = jur();

// ...

$response->issued();

// ...

$response->request('post')
    ->message('Task saved')
    ->data([
        'id' => 42
    ]);

// ...

$response->resolved();

// ...

echo $response->toJson();
```

```
{"message":"Task saved","request":"post","data":{"id":42},"debug":{"elapsed":10,"issued_at":1529618141842064,"resolved_at":1529618141842074}}
```