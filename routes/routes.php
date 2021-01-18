<?php

Route::group(
    [
        'prefix' => 'musora-api',
    ],
    function () {

        Route::get(
            '/content/{id}',
            \Railroad\MusoraApi\Controllers\ContentController::class . '@getContent'
        );
            });