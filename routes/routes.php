<?php

use Railroad\MusoraApi\Controllers\ContentController;
use Railroad\MusoraApi\Controllers\PacksController;
use Railroad\MusoraApi\Controllers\UserProgressController;

//authenticated user
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
                'as' => 'mobile.musora-api.content.show',
                'uses' => ContentController::class . '@getContent',
            ]
        );

        //filter contents
        Route::get(
            '/all',
            [
                'as' => 'mobile.musora-api.contents.filter',
                'uses' => ContentController::class . '@filterContents',
            ]
        );

        //in progress contents
        Route::get(
            '/in-progress',
            [
                'as' => 'mobile.musora-api.in-progress.contents',
                'uses' => ContentController::class . '@getInProgressContent',
            ]
        );

        //packs
        Route::get(
            '/packs',
            [
                'as' => 'mobile.musora-api.packs.show',
                'uses' => PacksController::class . '@showAllPacks',
            ]
        );

        Route::get(
            '/pack/{packId}',
            [
                'as' => 'mobile.musora-api.pack.show',
                'uses' => PacksController::class . '@showPack',
            ]
        );

        Route::get(
            '/pack/lesson/{lessonId}',
            [
                'as' => 'mobile.musora-api.pack.lesson.show',
                'uses' => PacksController::class . '@showLesson',
            ]
        );

        Route::get(
            '/packs/jump-to-next-lesson/{packContentId}',
            [
                'as' => 'mobile.musora-api.pack.jump-to-next-lesson',
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
                'as' => 'mobile.musora-api.learning-path.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showLearningPath',
            ]
        );

        Route::get(
            '/learning-path-levels/{learningPathSlug}/{levelSlug}',
            [
                'as' => 'mobile.musora-api.learning-path.level.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showLevel',
            ]
        );

        Route::get(
            '/learning-path-courses/{courseId}',
            [
                'as' => 'mobile.musora-api.learning-path.course.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showCourse',
            ]
        );

        Route::get(
            '/learning-path-lessons/{lessonId}',
            [
                'as' => 'mobile.musora-api.learning-path.lesson.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showLesson',
            ]
        );

        Route::get(
            '/learning-path-lesson/{lessonId}',
            [
                'as' => 'mobile.musora-api.learning-paths.unit-part.show',
                'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class . '@showUnitPart',
            ]
        );

        //route for live schedule
        Route::get(
            '/live-schedule',
            [
                'as' => 'mobile.musora-api.live-schedule',
                'uses' => ContentController::class . '@getLiveSchedule',
            ]
        );

        Route::get(
            '/schedule',
            [
                'as' => 'mobile.musora-api.content-schedule',
                'uses' => ContentController::class . '@getAllSchedule',
            ]
        );

        //live event
        Route::get(
            '/live-event',
            [
                'as' => 'mobile.musora-api.live-event',
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

        //get authenticated user
        Route::get('/profile', \Railroad\MusoraApi\Controllers\AuthController::class . '@getAuthUser');

        //update user profile
        Route::post('/profile/update', \Railroad\MusoraApi\Controllers\AuthController::class . '@updateUser');

        //add lessons to user playlist by default(onboarding screen)
        Route::put('/add-lessons', ContentController::class . '@addLessonsToUserList');

        //my list
        Route::get('/my-list', \Railroad\MusoraApi\Controllers\MyListController::class . '@getMyLists');

        //search
        Route::get(
            '/search',
            ContentController::class . '@search'
        );

        //routes for packs deep links - same as website's links + 'musora-api' prefix
        Route::get(
            '/members/packs/{packSlug}',
            [
                'as' => 'mobile.musora-api.packs.bundles.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForPack',
            ]
        );
        Route::get(
            '/members/packs/{packSlug}/bundle/{bundleSlug}/{bundleId}',
            [
                'as' => 'mobile.musora-api.pianote.pack.bundle.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForPianotePack',
            ]
        );

        //pianote
        Route::get(
            '/members/packs/{packSlug}/bundle/{bundleSlug}/{lessonSlug}/{lessonId}',
            [
                'as' => 'mobile.musora-api.pianote.pack.bundle.lesson.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForPianotePackBundleLesson',
            ]
        );
        Route::get(
            '/members/packs/{packSlug}/{bundleSlug}',
            [
                'as' => 'mobile.musora-api.pack.bundle.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForPack',
            ]
        );
        Route::get(
            '/members/packs/{packSlug}/{lessonSlug}/{lessonId}',
            [
                'as' => 'mobile.musora-api.pianote.pack.lesson.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForPianotePackLesson',
            ]
        )->where('lessonId', '[0-9]+');

        Route::get(
            '/members/packs/{packSlug}/{bundleSlug}/{lessonSlug}',
            [
                'as' => 'mobile.musora-api.packs.bundles.lessons.lesson.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForPack',
            ]
        );

        Route::get(
            '/members/semester-packs/{packSlug}',
            [
                'as' => 'mobile.musora-api.semester-packs.lessons.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForSemesterPack',
            ]
        );
        Route::get(
            '/members/semester-packs/{packSlug}/{lessonSlug}',
            [
                'as' => 'mobile.musora-api.semester-packs.lessons.show.deeplink',
                'uses' => PacksController::class . '@getDeepLinkForSemesterPack',
            ]
        );

        //routes for coaches deep links - same as website's links + 'musora-api' prefix
        Route::get(
            '/members/coaches/{coachSlug}',
            [
                'as' => 'mobile.musora-api.coaches.deeplink',
                'uses' => ContentController::class . '@getDeepLinkForCoach',
            ]
        );

        Route::put(
            '/follow',
            ContentController::class . '@followContent'
        )->name('mobile.musora-api.content.follow');

        Route::put(
            '/unfollow',
            ContentController::class . '@unfollowContent'
        )->name('mobile.musora-api.content.unfollow');

        Route::get(
            '/followed-content',
            ContentController::class . '@getFollowedContent'
        )->name('mobile.musora-api.followed.content');

        Route::get(
            '/followed-lessons',
            ContentController::class . '@getLessonsForFollowedCoaches'
        )->name('mobile.musora-api.followed.lessons');

        Route::get(
            '/featured-lessons',
            ContentController::class . '@getFeaturedLessons'
        )->name('mobile.musora-api.featured.lessons');

        Route::get(
            '/upcoming-coaches',
            [
                'as' => 'mobile.musora-api.upcoming.coaches',
                'uses' => ContentController::class . '@getUpcomingCoaches',
            ]
        );
    }
);

//guest user
Route::group(
    [
        'prefix' => 'musora-api',
        'middleware' => config('musora-api.user-middleware'),
    ],
    function () {
        //create intercom user
        Route::put('/intercom-user', \Railroad\MusoraApi\Controllers\AuthController::class . '@createIntercomUser');

        //login
        Route::put(
            '/login',
            [
                'middleware' => [
                    \Railroad\Ecommerce\Middleware\SyncInAppPurchasedItems::class,
                    \Railroad\MusoraApi\Middleware\AddMemberData::class,
                ],
                'uses' => \Railroad\Usora\Controllers\ApiController::class . '@login',
            ]
        );

        //forgot password
        Route::put('/forgot', \Railroad\Usora\Controllers\ApiController::class . '@forgotPassword');

        //reset password
        Route::put(
            '/change-password',
            [
                'middleware' => \Railroad\MusoraApi\Middleware\AddMemberData::class,
                'uses' => \Railroad\Usora\Controllers\ApiController::class . '@resetPassword',
            ]
        );
    }
);
