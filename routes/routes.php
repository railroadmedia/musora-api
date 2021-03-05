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

        //upload avatar
        Route::post(
            '/avatar/upload',
            \Railroad\MusoraApi\Controllers\AvatarController::class . '@put'
        );

        Route::get('/profile', \Railroad\MusoraApi\Controllers\AuthController::class . '@getAuthUser');

        //update user profile
        Route::post('/profile/update', \Railroad\MusoraApi\Controllers\AuthController::class . '@updateUser');

        Route::put('/add-lessons', ContentController::class . '@addLessonsToUserList');

        Route::get('/my-list', \Railroad\MusoraApi\Controllers\MyListController::class . '@getMyLists');

        Route::get(
            '/search',
            ContentController::class . '@search'
        );

    }
);

Route::group(
    [
        'prefix' => 'musora-api',
        'middleware' => config('musora-api.user-middleware'),
    ],
    function () {
        Route::put('/intercom-user', \Railroad\MusoraApi\Controllers\AuthController::class . '@createIntercomUser');

        Route::put('/forgot', \Railroad\Usora\Controllers\ApiController::class . '@forgotPassword');
        Route::put(
            '/change-password',
            [
                'middleware' => \Railroad\MusoraApi\Middleware\AddMemberData::class,
                'uses' => \Railroad\Usora\Controllers\ApiController::class . '@resetPassword'
            ]
        );
    }
);
