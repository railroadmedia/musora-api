<?php

use Railroad\MusoraApi\Controllers\ContentController;
use Railroad\MusoraApi\Controllers\PacksController;
use Railroad\MusoraApi\Controllers\UserProgressController;
use Railroad\Railcontent\Controllers\MyListJsonController;

//authenticated user
Route::group([
                 'prefix' => 'musora-api',
                 'middleware' => config('musora-api.auth-middleware'),
             ], function () {
    //content
    Route::get('/content/{id}', [
        'as' => 'mobile.musora-api.content.show',
        'uses' => ContentController::class.'@getContent',
    ]);

    //filter contents
    Route::get('/all', [
        'as' => 'mobile.musora-api.contents.filter',
        'uses' => ContentController::class.'@filterContents',
    ]);

    //in progress contents
    Route::get('/in-progress', [
        'as' => 'mobile.musora-api.in-progress.contents',
        'uses' => ContentController::class.'@getInProgressContent',
    ]);

    //packs
    Route::get('/packs', [
        'as' => 'mobile.musora-api.packs.show',
        'uses' => PacksController::class.'@showAllPacks',
    ]);

    Route::get('/pack/{packId}', [
        'as' => 'mobile.musora-api.pack.show',
        'uses' => PacksController::class.'@showPack',
    ]);

    Route::get('/pack/lesson/{lessonId}', [
        'as' => 'mobile.musora-api.pack.lesson.show',
        'uses' => PacksController::class.'@showLesson',
    ]);

    Route::get('/packs/jump-to-next-lesson/{packContentId}', [
        'as' => 'mobile.musora-api.pack.jump-to-next-lesson',
        'uses' => PacksController::class.'@jumpToNextLesson',
    ]);

    //save user progress
    Route::put(
        '/reset',
        UserProgressController::class.'@resetUserProgressOnContent'
    );

    //reset user progress
    Route::put(
        '/complete',
        UserProgressController::class.'@completeUserProgressOnContent'
    );

    //save video progress
    Route::put('/media', UserProgressController::class.'@saveVideoProgress');
    Route::put('/media/{id}', UserProgressController::class.'@saveVideoProgress');

    //learning path
    Route::get('/learning-paths/{learningPathSlug}', [
        'as' => 'mobile.musora-api.learning-path.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showLearningPath',
    ]);

    Route::get('/learning-path-levels/{learningPathSlug}/{levelSlug}', [
        'as' => 'mobile.musora-api.learning-path.level.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showLevel',
    ]);

    Route::get('/learning-path-courses/{courseId}', [
        'as' => 'mobile.musora-api.learning-path.course.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showCourse',
    ]);

    Route::get('/learning-path-lessons/{lessonId}', [
        'as' => 'mobile.musora-api.learning-path.lesson.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showLesson',
    ]);

    Route::get('/learning-path-lesson/{lessonId}', [
        'as' => 'mobile.musora-api.learning-paths.unit-part.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showUnitPart',
    ]);

    //route for live schedule
    Route::get('/live-schedule', [
        'as' => 'mobile.musora-api.live-schedule',
        'uses' => ContentController::class.'@getLiveSchedule',
    ]);

    Route::get('/schedule', [
        'as' => 'mobile.musora-api.content-schedule',
        'uses' => ContentController::class.'@getAllSchedule',
    ]);

    //live event
    Route::get('/live-event', [
        'as' => 'mobile.musora-api.live-event',
        'uses' => \Railroad\MusoraApi\Controllers\LiveController::class.'@getLiveEvent',
    ]);

    Route::post(
        '/submit-question',
        ContentController::class.'@submitQuestion'
    );

    Route::post(
        '/submit-video',
        ContentController::class.'@submitVideo'
    );

    Route::post(
        '/submit-student-focus-form',
        ContentController::class.'@submitStudentFocusForm'
    );

    //upload avatar
    Route::post(
        '/avatar/upload',
        \Railroad\MusoraApi\Controllers\AvatarController::class.'@put'
    );

    //get authenticated user
    Route::get('/profile', \Railroad\MusoraApi\Controllers\AuthController::class.'@getAuthUser');

    //update user profile
    Route::post('/profile/update', \Railroad\MusoraApi\Controllers\AuthController::class.'@updateUser');

    //add lessons to user playlist by default(onboarding screen)
    Route::put('/add-lessons', ContentController::class.'@addLessonsToUserList');

    //my list
    Route::get('/my-list', \Railroad\MusoraApi\Controllers\MyListController::class.'@getMyLists');

    //search
    Route::get(
        '/search',
        ContentController::class.'@search'
    );

    //routes for packs deep links - same as website's links + 'musora-api' prefix
    Route::get('/members/packs/{packSlug}', [
        'as' => 'mobile.musora-api.packs.bundles.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPack',
    ]);
    Route::get('/members/packs/{packSlug}/bundle/{bundleSlug}/{bundleId}', [
        'as' => 'mobile.musora-api.pianote.pack.bundle.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPianotePack',
    ]);

    //pianote
    Route::get('/members/packs/{packSlug}/bundle/{bundleSlug}/{lessonSlug}/{lessonId}', [
        'as' => 'mobile.musora-api.pianote.pack.bundle.lesson.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPianotePackBundleLesson',
    ]);
    Route::get('/members/packs/{packSlug}/{bundleSlug}', [
        'as' => 'mobile.musora-api.pack.bundle.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPack',
    ]);
    Route::get('/members/packs/{packSlug}/{lessonSlug}/{lessonId}', [
        'as' => 'mobile.musora-api.pianote.pack.lesson.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPianotePackLesson',
    ])
        ->where('lessonId', '[0-9]+');

    Route::get('/members/packs/{packSlug}/{bundleSlug}/{lessonSlug}', [
        'as' => 'mobile.musora-api.packs.bundles.lessons.lesson.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPack',
    ]);

    Route::get('/members/semester-packs/{packSlug}', [
        'as' => 'mobile.musora-api.semester-packs.lessons.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForSemesterPack',
    ]);
    Route::get('/members/semester-packs/{packSlug}/{lessonSlug}', [
        'as' => 'mobile.musora-api.semester-packs.lessons.show.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForSemesterPack',
    ]);

    //routes for coaches deep links - same as website's links + 'musora-api' prefix
    Route::get('/members/coaches/{coachSlug}', [
        'as' => 'mobile.musora-api.coaches.deeplink',
        'uses' => ContentController::class.'@getDeepLinkForCoach',
    ]);

    Route::put(
        '/follow',
        ContentController::class.'@followContent'
    )
        ->name('mobile.musora-api.content.follow');

    Route::put(
        '/unfollow',
        ContentController::class.'@unfollowContent'
    )
        ->name('mobile.musora-api.content.unfollow');

    Route::get(
        '/followed-content',
        ContentController::class.'@getFollowedContent'
    )
        ->name('mobile.musora-api.followed.content');

    Route::get(
        '/followed-lessons',
        ContentController::class.'@getLessonsForFollowedCoaches'
    )
        ->name('mobile.musora-api.followed.lessons');

    Route::get(
        '/featured-lessons',
        ContentController::class.'@getFeaturedLessons'
    )
        ->name('mobile.musora-api.featured.lessons');

    Route::get('/upcoming-coaches', [
        'as' => 'mobile.musora-api.upcoming.coaches',
        'uses' => ContentController::class.'@getUpcomingCoaches',
    ]);

    Route::get('/jump-to-continue-content/{contentId}', [ContentController::class, 'jumpToContinueContent'])
        ->name('mobile.musora-api.jump-to-continue-content');

    Route::get(
        '/content-meta',
        ContentController::class.'@getContentMeta'
    )
        ->name('content.meta.data');

    Route::post(
        '/request-song',
        Railroad\Railcontent\Controllers\RequestedSongsJsonController::class.'@requestSong'
    )
        ->name('mobile.musora-api.request.song');
});

//guest user
Route::group([
                 'prefix' => 'musora-api',
                 'middleware' => config('musora-api.user-middleware'),
             ], function () {
    //create intercom user
    Route::put('/intercom-user', \Railroad\MusoraApi\Controllers\AuthController::class.'@createIntercomUser');

    //login
    Route::put('/login', [
        'middleware' => [
            \Railroad\Ecommerce\Middleware\SyncInAppPurchasedItems::class,
            \Railroad\MusoraApi\Middleware\AddMemberData::class,
        ],
        // 'uses' => \Railroad\Usora\Controllers\ApiController::class . '@login',
    ]);

    Route::get(
        '/revenuecat-user',
        \Railroad\MusoraApi\Controllers\AuthController::class.'@getUserAfterRevenuecatPurchase'
    )
        ->name('mobile.musora-api.v1.auth.revenuecat-user');
    //forgot password
    //  Route::put('/forgot', \Railroad\Usora\Controllers\ApiController::class . '@forgotPassword');

    //reset password
    //        Route::put(
    //            '/change-password',
    //            [
    //                'middleware' => \Railroad\MusoraApi\Middleware\AddMemberData::class,
    //                'uses' => \Railroad\Usora\Controllers\ApiController::class . '@resetPassword',
    //            ]
    //        );
});

Route::group([
                 'as' => 'v1.',
                 'prefix' => 'musora-api/v1',
                 'middleware' => array_merge(config('musora-api.auth-middleware', []), ['api_version:v1']),
             ], function () {
    //content
    Route::get(
        '/content/{id}',
        ContentController::class.'@getContentOptimised'
    )
        ->name('mobile.musora-api.content.show');

    //filter contents
    Route::get('/all', [
        'as' => 'mobile.musora-api.contents.filter',
        'uses' => ContentController::class.'@filterContents',
    ]);

    //in progress contents
    Route::get('/in-progress', [
        'as' => 'mobile.musora-api.in-progress.contents',
        'uses' => ContentController::class.'@getInProgressContent',
    ]);

    //packs
    Route::get('/packs', [
        'as' => 'mobile.musora-api.packs',
        'uses' => PacksController::class.'@showAllPacks',
    ]);

    Route::get('/pack/{packId}', [
        'as' => 'mobile.musora-api.pack.show',
        'uses' => PacksController::class.'@showPack',
    ]);

    Route::get('/pack/lesson/{lessonId}', [
        'as' => 'mobile.musora-api.pack.lesson.show',
        'uses' => PacksController::class.'@showLesson',
    ]);

    Route::get('/packs/jump-to-next-lesson/{packContentId}', [
        'as' => 'mobile.musora-api.pack.jump-to-next-lesson',
        'uses' => PacksController::class.'@jumpToNextLesson',
    ]);

    //save user progress
    Route::put(
        '/reset',
        UserProgressController::class.'@resetUserProgressOnContentModified'
    );

    //reset user progress
    Route::put(
        '/complete',
        UserProgressController::class.'@completeUserProgressOnContent'
    );

    //save video progress
    Route::put('/media', UserProgressController::class.'@saveVideoProgress');
    Route::put('/media/{id}', UserProgressController::class.'@saveVideoProgress');

    //learning path
    Route::get('/learning-paths/{learningPathSlug}', [
        'as' => 'mobile.musora-api.learning-path.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showLearningPath',
    ]);

    Route::get('/learning-path-levels/{learningPathSlug}/{levelSlug}', [
        'as' => 'mobile.musora-api.learning-path.level.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showLevel',
    ]);

    Route::get('/learning-path-courses/{courseId}', [
        'as' => 'mobile.musora-api.learning-path.course.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showCourse',
    ]);

    Route::get('/learning-path-lessons/{lessonId}', [
        'as' => 'mobile.musora-api.learning-path.lesson.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showLesson',
    ]);

    Route::get('/learning-path-lesson/{lessonId}', [
        'as' => 'mobile.musora-api.learning-paths.unit-part.show',
        'uses' => \Railroad\MusoraApi\Controllers\LearningPathController::class.'@showUnitPart',
    ]);

    //route for live schedule
    Route::get('/live-schedule', [
        'as' => 'mobile.musora-api.live-schedule',
        'uses' => ContentController::class.'@getLiveScheduleOptimised',
    ]);

    Route::get('/schedule', [
        'as' => 'mobile.musora-api.content-schedule',
        'uses' => ContentController::class.'@getAllScheduleOptimised',
    ]);

    //live event
    Route::get('/live-event', [
        'as' => 'mobile.musora-api.live-event',
        'uses' => \Railroad\MusoraApi\Controllers\LiveController::class.'@getLiveEvent',
    ]);

    Route::post(
        '/submit-question',
        ContentController::class.'@submitQuestion'
    );

    Route::post(
        '/submit-video',
        ContentController::class.'@submitVideo'
    );

    Route::post(
        '/submit-student-focus-form',
        ContentController::class.'@submitStudentFocusForm'
    );

    //upload avatar
    Route::post(
        '/avatar/upload',
        \Railroad\MusoraApi\Controllers\AvatarController::class.'@put'
    );

    //get authenticated user
    Route::get('/profile', \Railroad\MusoraApi\Controllers\AuthController::class.'@getAuthUser');

    //update user profile
    Route::post('/profile/update', \Railroad\MusoraApi\Controllers\AuthController::class.'@updateUser');

    //add lessons to user playlist by default(onboarding screen)
    Route::put('/add-lessons', ContentController::class.'@addLessonsToUserList');

    //my list
    Route::get('/my-list', \Railroad\MusoraApi\Controllers\MyListController::class.'@getMyLists');

    //search
    Route::get(
        '/search',
        ContentController::class.'@search'
    );

    //routes for packs deep links - same as website's links + 'musora-api' prefix
    Route::get('/members/packs/{packSlug}', [
        'as' => 'mobile.musora-api.packs.bundles.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPack',
    ]);
    Route::get('/members/packs/{packSlug}/bundle/{bundleSlug}/{bundleId}', [
        'as' => 'mobile.musora-api.pianote.pack.bundle.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPianotePack',
    ]);

    //pianote
    Route::get('/members/packs/{packSlug}/bundle/{bundleSlug}/{lessonSlug}/{lessonId}', [
        'as' => 'mobile.musora-api.pianote.pack.bundle.lesson.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPianotePackBundleLesson',
    ]);
    Route::get('/members/packs/{packSlug}/{bundleSlug}', [
        'as' => 'mobile.musora-api.pack.bundle.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPack',
    ]);
    Route::get('/members/packs/{packSlug}/{lessonSlug}/{lessonId}', [
        'as' => 'mobile.musora-api.pianote.pack.lesson.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPianotePackLesson',
    ])
        ->where('lessonId', '[0-9]+');

    Route::get('/members/packs/{packSlug}/{bundleSlug}/{lessonSlug}', [
        'as' => 'mobile.musora-api.packs.bundles.lessons.lesson.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForPack',
    ]);

    Route::get('/members/semester-packs/{packSlug}', [
        'as' => 'mobile.musora-api.semester-packs.lessons.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForSemesterPack',
    ]);
    Route::get('/members/semester-packs/{packSlug}/{lessonSlug}', [
        'as' => 'mobile.musora-api.semester-packs.lessons.show.deeplink',
        'uses' => PacksController::class.'@getDeepLinkForSemesterPack',
    ]);

    //routes for coaches deep links - same as website's links + 'musora-api' prefix
    Route::get('/members/coaches/{coachSlug}', [
        'as' => 'mobile.musora-api.coaches.deeplink',
        'uses' => ContentController::class.'@getDeepLinkForCoach',
    ]);

    Route::put(
        '/follow',
        ContentController::class.'@followContent'
    )
        ->name('mobile.musora-api.content.follow');

    Route::put(
        '/unfollow',
        ContentController::class.'@unfollowContent'
    )
        ->name('mobile.musora-api.content.unfollow');

    Route::get(
        '/followed-content',
        ContentController::class.'@getFollowedContent'
    )
        ->name('mobile.musora-api.followed.content');

    Route::get(
        '/followed-lessons',
        ContentController::class.'@getLessonsForFollowedCoaches'
    )
        ->name('mobile.musora-api.followed.lessons');

    Route::get(
        '/featured-lessons',
        ContentController::class.'@getFeaturedLessons'
    )
        ->name('mobile.musora-api.featured.lessons');

    Route::get('/upcoming-coaches', [
        'as' => 'mobile.musora-api.upcoming.coaches',
        'uses' => ContentController::class.'@getUpcomingCoaches',
    ]);

    Route::get('/jump-to-continue-content/{contentId}', [ContentController::class, 'jumpToContinueContent'])
        ->name('mobile.musora-api.jump-to-continue-content');

    Route::get(
        '/content-meta',
        ContentController::class.'@getContentMeta'
    )
        ->name('content.meta.data');

    Route::get(
        '/guitar-quest-map',
        ContentController::class.'@getGuitarQuestMap'
    )
        ->name('guitar.quest.map');

    Route::get(
        '/routine-trailer',
        ContentController::class.'@getRoutinesTrailer'
    )
        ->name('singeo.routine.trailer');
    Route::get(
        '/homepage-banner',
        ContentController::class.'@getHomepageBanner'
    )
        ->name('homepage.banner');

    Route::put('/delete-account', [
                                    'as' => 'mobile.musora-api.delete.account',
                                    'uses' => \Railroad\MusoraApi\Controllers\AuthController::class.'@deleteAccount',
                                ]);

    Route::post(
        '/request-song',
        Railroad\Railcontent\Controllers\RequestedSongsJsonController::class . '@requestSong'
    )->name('mobile.musora-api.v1.request.song');

    /**
     * Playlists v2 Routes
     */
    Route::get('/playlists', [
        'as' => 'mobile.musora-api.playlists.show',
        'uses' => \Railroad\Railcontent\Controllers\MyListJsonController::class . '@getUserPlaylists',
    ]);

    Route::post('/playlist', \Railroad\Railcontent\Controllers\MyListJsonController::class . '@createPlaylist');

    Route::get('/playlist', \Railroad\MusoraApi\Controllers\MyListController::class . '@getPlaylist')->name('mobile.musora-api.get.playlist');

    Route::put('/copy-playlist', MyListJsonController::class . '@copyPlaylist')->name('mobile.musora-api.copy.playlist');

    Route::patch(
        '/playlist/{id}',
        MyListJsonController::class . '@updatePlaylist'
    )
        ->name('mobile.musora-api.update.playlist');

    Route::get('/public-playlists', MyListJsonController::class . '@getPublicPlaylists');

    Route::put(
        '/pin-playlist',
        MyListJsonController::class . '@pinPlaylist'
    )->name('mobile.musora-api.pin.playlist');

    Route::get('/my-pinned-playlists', MyListJsonController::class . '@getPinnedPlaylists');

    Route::put(
        '/unpin-playlist',
        MyListJsonController::class . '@unpinPlaylist'
    )->name('mobile.musora-api.unpin.playlist');

    Route::put(
        '/like-playlist',
        MyListJsonController::class . '@likePlaylist'
    )->name('mobile.musora-api.like.playlist');

    Route::delete(
        '/like-playlist',
        MyListJsonController::class . '@deletePlaylistLike'
    )->name('mobile.musora-api.delete.playlist.like');

    Route::get('/liked-playlists', MyListJsonController::class . '@getLikedPlaylists')->name('mobile.musora-api.user.liked.playlists');

    Route::get('/playlist-lessons', \Railroad\MusoraApi\Controllers\MyListController::class . '@getPlaylistLessons');

    Route::put('/change-playlist-content', \Railroad\MusoraApi\Controllers\MyListController::class . '@changePlaylistContent');
    Route::put('/add-item-to-list', \Railroad\MusoraApi\Controllers\MyListController::class . '@addItemToPlaylist');
    Route::delete('/remove-item-from-list', \Railroad\MusoraApi\Controllers\MyListController::class . '@removeItemFromPlaylist');
    Route::delete('/playlist',MyListJsonController::class . '@deletePlaylist')->name('mobile.musora-api.delete.playlist');
    Route::get('/lessons-and-assignments-count/{contentId}', \Railroad\Railcontent\Controllers\ContentJsonController::class . '@countLessonsAndAssignments')->name('mobile.musora-api.content.assignments.count');
    Route::get('/search-playlist',MyListJsonController::class . '@searchPlaylist')->name('mobile.musora-api.search.playlist');
    Route::get('/play/{playlistId}', [ContentController::class  , 'jumpToPlay'])
        ->name('mobile.musora-api.jump-to-play');
    Route::post(
        '/upload-playlist-thumb',
        MyListJsonController::class . '@uploadPlaylistThumbnail'
    );
    Route::get('/playlist-item', ContentController::class . '@getPlaylistItem')->name('mobile.musora-api.deeplink.playlist.item');

    Route::get(
        '/cohort',
        \Railroad\MusoraApi\Controllers\CohortPackController::class . '@getTemplate'
    )
        ->name('mobile.musora-api.cohort.template');

    Route::get(
        '/cohort-banner',
        \Railroad\MusoraApi\Controllers\CohortPackController::class . '@getBanner'
    )
        ->name('mobile.musora-api.cohort.banner');

    Route::get(
        '/auth-key',
        \Railroad\MusoraApi\Controllers\AuthController::class.'@getTempToken'
    )
        ->name('mobile.musora-api.v1.auth.key');

    Route::put('/playlist/report/{id}', \Railroad\MusoraApi\Controllers\MyListController::class.'@reportPlaylist')
        ->name('mobile.musora-api.v1.playlist.report');

    Route::post('/content/report', ContentController::class.'@report')
        ->name('mobile.musora-api.v1.content.report');

    Route::get('/homepage-learning-paths',
        \Railroad\MusoraApi\Controllers\LearningPathsSectionController::class . '@getLearningPaths'
    )
        ->name('mobile.musora-api.homepage.learning-paths');
    Route::get('/style', [
        'as' => 'mobile.musora-api.genre.collection.page',
        'uses' => ContentController::class.'@genreCollectionPage',
    ]);

    Route::get('/artist', [
        'as' => 'mobile.musora-api.artist.collection.page',
        'uses' => ContentController::class.'@artistCollectionPage',
    ]);

    Route::get('/artists', [
        'as' => 'mobile.musora-api.artists.page',
        'uses' => ContentController::class.'@artistsPage',
    ]);
});

Route::group([
                 'as' => 'v2.',
                 'prefix' => 'musora-api/v2',
                 'middleware' => array_merge(config('musora-api.auth-middleware', []), ['api_version:v2']),
             ], function () {
    Route::get(
        '/homepage-banner',
        ContentController::class.'@getHomepageBanner'
    )
        ->name('homepage.banner');

    Route::get('/playlist-lessons', \Railroad\MusoraApi\Controllers\MyListController::class . '@getPlaylistLessons');

    //update user profile
    Route::post('/profile/update', \Railroad\MusoraApi\Controllers\AuthController::class.'@updateUser');
});

Route::group([
                 'as' => 'v3.',
                 'prefix' => 'musora-api/v3',
                 'middleware' => array_merge(config('musora-api.auth-middleware', []), ['api_version:v3']),
             ], function () {
    Route::get(
        '/homepage-banner',
        ContentController::class . '@getHomepageBanner'
    )
        ->name('homepage.banner');
});
Route::group([
                 'as' => 'v4.',
                 'prefix' => 'musora-api/v4',
                 'middleware' => array_merge(config('musora-api.auth-middleware', []), ['api_version:v4']),
             ], function () {
    Route::get(
        '/homepage-banner',
        ContentController::class . '@getHomepageBanner'
    )
        ->name('homepage.banner');
});
Route::group([
                 'as' => 'v5.',
                 'prefix' => 'musora-api/v5',
                 'middleware' => array_merge(config('musora-api.auth-middleware', []), ['api_version:v5']),
             ], function () {
    Route::get(
        '/homepage-banner',
        ContentController::class . '@getHomepageBanner'
    )
        ->name('homepage.banner');
});


