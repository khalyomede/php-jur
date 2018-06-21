<?php
    require(__DIR__ . '/../vendor/autoload.php');

    $response = jur()
        ->request('get')
        ->data([
            ['id' => 1, 'name' => 'New in PHP 7.2', 'author' => 'Carlo Daniele'],
            ['id' => 2, 'name' => 'Help for new PHP project', 'author' => 'Khalyomede']
        ])
        ->toObject();

    print_r($response);
?>