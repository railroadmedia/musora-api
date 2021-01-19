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
            [
                'as' => 'mobile.content.show',
                'uses' => ContentController::class . '@getContent',
            ]
        );

        Route::get(
            '/all',
            [
                'as' => 'mobile.contents.filter',
                'uses' => ContentController::class . '@filterContents',
            ]
        );

        Route::get(
            '/packs',
            [
                'as' => 'mobile.members.packs.show',
                'uses' => ContentController::class . '@showAllPacks',
            ]
        );
    }
);