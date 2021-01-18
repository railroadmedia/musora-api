<?php

use Railroad\MusoraApi\Controllers\ContentController;

Route::group(
    [
        'prefix' => 'musora-api',
        'middleware' => config('musora-api.middleware'),
    ],
    function () {

        Route::get(
            '/content/{id}',
            ContentController::class . '@getContent'
        );
    }
);