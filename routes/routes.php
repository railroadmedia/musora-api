<?php

use Railroad\MusoraApi\Controllers\ContentController;
use Railroad\MusoraApi\Controllers\PacksController;
use Railroad\MusoraApi\Controllers\UserProgressController;
use Railroad\MusoraApi\Controllers\AppleController;

Route::group(
    [
        'prefix' => 'musora-api',
        'middleware' => config('musora-api.auth-middleware'),
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
                'as' => 'mobile.pack.jump-to-next-lesson',
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

        //route for live schedule
        Route::get(
            '/live-schedule',
            [
                'as' => 'mobile.live-schedule',
                'uses' => ContentController::class . '@getLiveSchedule',
            ]
        );

        Route::get(
            '/schedule',
            [
                'as' => 'mobile.content-schedule',
                'uses' => ContentController::class . '@getAllSchedule',
            ]
        );

        //live event
        Route::get(
            '/live-event',
            [
                'as' => 'mobile.live-event',
                'uses' => \Railroad\MusoraApi\Controllers\LiveController::class . '@getLiveEvent',
            ]
        );

        Route::post(
            '/submit-question',
            ContentController::class . '@submitQuestion'
        );

        Route::post(
            '/submit-video',
            ContentController::class . '@submitVideo'
        );

        Route::post(
            '/submit-student-focus-form',
            ContentController::class . '@submitStudentFocusForm'
        );

    }
);

Route::group(
    [
        'prefix' => 'musora-api',
        'middleware' => config('musora-api.auth-middleware'),
    ],
    function () {
        Route::post(
            '/apple/verify-receipt-and-process-payment',
            AppleController::class . '@verifyReceiptAndProcessPayment'
        );
        Route::post(
            '/apple/signup',
            AppleController::class . '@signup'
        );
        Route::post('/apple/restore', AppleController::class . '@restorePurchase');
    }
);