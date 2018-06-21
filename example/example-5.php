<?php
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
?>