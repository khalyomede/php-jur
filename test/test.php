<?php

require __DIR__ . '/../vendor/autoload.php';

use Khalyomede\JUR;

$jur = JUR::put()
	->success()
	->message('test')
	->data([['name' => 'John Doe'], ['name' => 'Elizabeth Jones']])
	->toObject();