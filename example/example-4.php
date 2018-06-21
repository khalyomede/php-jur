<?php
    require(__DIR__ . '/../vendor/autoload.php');

    $response = jur()
        ->request('post')
        ->message('Task saved.')
        ->data([
            'id' => 42
        ])
        ->toJson();

    echo $response;
?>