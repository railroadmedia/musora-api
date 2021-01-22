<?php

use Railroad\MusoraApi\Controllers\ContentController;
use Railroad\MusoraApi\Controllers\PacksController;
use Railroad\MusoraApi\Controllers\UserProgressController;

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
                'as' => 'mobile.packs.show',
                'uses' => PacksController::class . '@showAllPacks',
            ]
        );

        Route::get(
            '/pack/{packId}',
            [
                'as' => 'mobile.pack.show',
                'uses' => PacksController::class . '@showPack',
            ]
        );

        Route::get(
            '/pack/lesson/{lessonId}',
            [
                'as' => 'mobile.pack.lesson.show',
                'uses' => PacksController::class . '@showLesson',
            ]
        );

        Route::put(
            '/reset',
            UserProgressController::class . '@resetUserProgressOnContent'
        );

        Route::put(
            '/complete',
            UserProgressController::class . '@completeUserProgressOnContent'
        );

        Route::put('/media', UserProgressController::class . '@saveVideoProgress');
        Route::put('/media/{id}', UserProgressController::class . '@saveVideoProgress');

    }
);