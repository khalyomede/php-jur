<?php

require __DIR__ . '/../vendor/autoload.php';

use Khalyomede\JUR;

JUR::reset()
	->requested();

$response = JUR::put()
	->success()
	->message('test')
	->data([['name' => 'John Doe'], ['name' => 'Elizabeth Jones']])
	->resolved()
	->toObject();

print_r($response);