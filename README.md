# musora-api

API for musora websites

- [Install](#install)
- [API Endpoints](#api-endpoints)
    * [Guest Endpoints](#guest-endpoints)
    * [Endpoints that require Authentication](#endpoints-that-require-authentication)

<!-- ecotrust-canada.github.io/markdown-toc -->

Install
------------------------------------------------------------------------------------------------------------------------

1. Install via composer:

> composer require railroad/musora-api:1.0

2. Add service provider to your application laravel config app.php file:

```php
use Railroad\MusoraApi\Providers\MusoraApiServiceProvider;'providers' => [
    
    // ... other providers
     MusoraApiServiceProvider::class,
],
```

3. Publish the musora-api config file:

> php artisan vendor:publish

4. Define middlewares, response structure and emails messages in musora-api.php config file(
   e.g.: [Drumeo file](https://github.com/railroadmedia/drumeo/blob/musora-api/laravel/config/musora-api.php))


5. Create chat, user and product providers
   (
   e.g: [MusoraApiChatProvider](https://github.com/railroadmedia/drumeo/blob/musora-api/laravel/app/Providers/MusoraApiChatProvider.php)
   ,
   [MusoraApiUserProvider](https://github.com/railroadmedia/drumeo/blob/musora-api/laravel/app/Providers/MusoraApiUserProvider.php)
   ,
   [MusoraApiProductProvider](https://github.com/railroadmedia/drumeo/blob/musora-api/laravel/app/Providers/MusoraApiProductProvider.php))


6. In AppServiceProvider boot method create instance for the providers:

```php
        app()->instance(ProductProviderInterface::class, app()->make(MusoraApiProductProvider::class));
        app()->instance(ChatProviderInterface::class, app()->make(MusoraApiChatProvider::class));
        app()->instance(UserProviderInterface::class, app()->make(MusoraApiUserProvider::class));
```

API Endpoints
------------------------------------------------------------------------------------------------------------------------

### Guest Endpoints

|                                                          | Endpoints                                               |
|:-----------------------------------------------------------------|:----------------------------------------------------------|
| [Login](docs/Login.md)                              | `/musora-api/login`                                               |
| [Forgot password](docs/ForgotPassword.md)                  | `/musora-api/forgot`                                              |
| [Change password](docs/ChangePassword.md)                        | `/musora-api/change-password`                                     |
| [Create Intercom user](docs/Intercom.md)                   | `/musora-api/intercom-user`                                       |


### Endpoints that require Authentication

|                                                                       | Endpoints                                                         |
|:----------------------------------------------------------------------|:------------------------------------------------------------------|
| [Catalogues (filter contents)](docs/AllContents.md)                   | `/musora-api/all`                                                 |
| [In-progress lists](docs/InProgress.md)                               | `/musora-api/in-progress`                                         |
| [Pull content](docs/Content.md)                                       | `/musora-api/content/{contentId}`                                 |
| [Search](docs/Search.md)                                              | `/musora-api/search`                                              |
| [My lists](docs/MyList.md)                                            | `/musora-api/my-list`                                             |
| &nbsp;                                                                |                                                                   |
| [Packs list](docs/Packs.md)                                           | `/musora-api/packs`                                               |
| [Get pack](docs/Pack.md)                                              | `/musora-api/pack/{packId}`                                       |
| [Get pack's lesson](docs/PackLesson.md)                               | `/musora-api/pack/lesson/{lessonId}`                              |
| [Get next pack lesson](docs/NextPackLesson.md)                        | `/musora-api/packs/jump-to-next-lesson/{packId}`                  |
| &nbsp;                                                                |                                                                   |
| [Leaning path(Method)](docs/LearningPath.md)                          | `/musora-api/learning-paths/{learningPathSlug}`                   |
| [Learning path level](docs/LearningPathLevel.md)                      | `/musora-api/learning-path-levels/{learningPathSlug}/{levelSlug}` |
| [Leaning path course](docs/LearningPathCourse.md)                     | `/musora-api/learning-path-courses/{courseId}`                    |
| [Learning path lesson](docs/LearningPathLesson.md)                    | `/musora-api/learning-path-lessons/{lessonId}`                    |
| &nbsp;                                                                | &nbsp;                                                            |
| [Schedule list](docs/Shedule.md)                                      | `/musora-api/schedule`                                            |
| [Live schedule list](docs/LiveShedule.md)                             | `/musora-api/live-schedule`                                       |
| [Live event](docs/Live.md)                                            | `/musora-api/live-event`                                          |
| &nbsp;                                                                | &nbsp;                                                            |
| [Mark content as completed](docs/MarkAsComplete.md)                   | `/musora-api/complete`                                            |
| [Reset user content progress](docs/ResetProgress.md)                  | `/musora-api/reset`                                               |
| &nbsp;                                                                | &nbsp;                                                            |
| [Recommended Content](docs/RecommendedContent.md)                     | `/musora-api/recommended`                                         |
| &nbsp;                                                                | &nbsp;                                                            |
| [Track media](docs/TrackMedia.md)                                     | `/musora-api/media`                                               |
| [Save video progress](docs/SaveVideoProgress.md)                      | `/musora-api/media/{sessionId}`                                   |
| &nbsp;                                                                | &nbsp;                                                            |
| [Submit question](docs/SubmitQuestion.md)                             | `/musora-api/submit-question`                                     |
| [Submit video](docs/SubmitVideo.md)                                   | `/musora-api/submit-video`                                        |
| [Submit student focus](docs/SubmitStudentFocus.md)                    | `/musora-api/submit-student-focus-form`                           |
| &nbsp;                                                                | &nbsp;                                                            |
| [Get authenticated user profile](docs/GetAuthenticatedUserProfile.md) | `/musora-api/profile`                                             |
| [Upload avatar](docs/UploadAvatar.md)                                 | `/musora-api/avatar/upload`                                       |
| [Update user profile](docs/UpdateUserProfile.md)                      | `/musora-api/profile/update`                                      |
| &nbsp;                                                                | &nbsp;                                                            |
| [Add default lessons to user's list](docs/AddDefaultLesson.md)        | `/musora-api/add-lessons`                                         |
| &nbsp;                                                                | &nbsp;                                                            |
| [Get featured coaches](docs/FeaturedCoach.md)                         | `/musora-api/all`                                                 |
| [Get active coaches](docs/ActiveCoach.md)                             | `/musora-api/all`                                                 |
| [Get subscribed coaches](docs/FollowedCoaches.md)                     | `/musora-api/followed-content`                                    |
| [Get latest subscribed lessons](docs/LatestLessons.md)                | `/musora-api/followed-lessons`                                    |
| [Get latest featured lessons](docs/LatestFeaturedLessons.md)          | `/musora-api/followed-lessons`                                    |
| [Get all coaches](docs/AllCoaches.md)                                 | `/musora-api/all`                                                 |
| [Subscribe to/follow coach](docs/FollowContent.md)                    | `/musora-api/follow`                                              |
| [Unsubscribe/unfollow coach](docs/UnFollowContent.md)                 | `/musora-api/unfollow`                                            |

#### Endpoints available starting with v1 
|                                                                         | Endpoints                                             |
|:------------------------------------------------------------------------|:------------------------------------------------------|
| [Get Routines trailer](docs/RoutinesTrailer.md)                         | `/musora-api/v1/routine-trailer`                      |
| [Get meta data for specific content type](docs/ContentMetaData.md)      | `/musora-api/v1/content-meta`                         |
| [Get next incomplete lesson for course/pack/method](docs/NextLesson.md) | `/musora-api/v1/jump-to-continue-content/{contentId}` |
| [Get Guitar Quest data](docs/GuitarQuestData.md)                        | `/musora-api/v1/guitar-quest-map`                     |
| [Get Homepage banner data](docs/HomepageBannerData.md)                  | `/musora-api/v1/homepage-banner`                      |
| [Get New Song Releases](docs/NewReleases.md)                            | `/musora-api/v1/content-schedule/new-releases`        |
| [Get Coming Soon Content](docs/ComingSoon.md)                           | `/musora-api/v1/content-schedule/coming-soon`         |
| [Get Removed Content](docs/Removed.md)                                  | `/musora-api/v1/content-schedule/removed`             |

