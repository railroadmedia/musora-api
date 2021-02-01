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
        //content
        Route::get(
            '/content/{id}',
            [
                'as' => 'mobile.content.show',
                'uses' => ContentController::class . '@getContent',
            ]
        );

        //filter contents
        Route::get(
            '/all',
            [
                'as' => 'mobile.contents.filter',
                'uses' => ContentController::class . '@filterContents',
            ]
        );

        //in progress contents
        Route::get(
            '/in-progress',
            [
                'as' => 'mobile.in-progress.contents',
                'uses' => ContentController::class . '@getInProgressContent',
            ]
        );

        //packs
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

        Route::get(
            '/packs/jump-to-next-lesson/{packContentId}',
            [
                'as' => 'mobile.packs.jump-to-next-lesson',
                'uses' => PacksController::class . '@jumpToNextLesson',
            ]
        );


        //save user progress
        Route::put(
            '/reset',
            UserProgressController::class . '@resetUserProgressOnContent'
        );

        //reset user progress
        Route::put(
            '/complete',
            UserProgressController::class . '@completeUserProgressOnContent'
        );

        //save video progress
        Route::put('/media', UserProgressController::class . '@saveVideoProgress');
        Route::put('/media/{id}', UserProgressController::class . '@saveVideoProgress');

        //learning path
        Route::get(
            '/learning-paths/{learningPathSlug}',
            [
                'as' => 'mobile.learning-path.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showLearningPath',
            ]
        );

        Route::get(
            '/learning-path-levels/{learningPathSlug}/{levelSlug}',
            [
                'as' => 'mobile.learning-path.level.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showLevel',
            ]
        );

        Route::get(
            '/learning-path-courses/{courseId}',
            [
                'as' => 'mobile.learning-path.course.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showCourse',
            ]
        );

        Route::get(
            '/learning-path-lessons/{lessonId}',
            [
                'as' => 'mobile.learning-path.lesson.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showLesson',
            ]
        );

    }
);