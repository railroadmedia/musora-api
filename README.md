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
'providers' => [
    
    // ... other providers
     \Railroad\MusoraApi\Providers\MusoraApiServiceProvider::class,
],
```
3. Publish the musora-api config file:
> php artisan vendor:publish

4. Define middlewares, response structure and emails messages in musora-api.php config file(e.g.: [Drumeo file](https://github.com/railroadmedia/drumeo/blob/musora-api/laravel/config/musora-api.php))


5. Create chat, user and product providers
   (e.g: [MusoraApiChatProvider](https://github.com/railroadmedia/drumeo/blob/musora-api/laravel/app/Providers/MusoraApiChatProvider.php),
   [MusoraApiUserProvider](https://github.com/railroadmedia/drumeo/blob/musora-api/laravel/app/Providers/MusoraApiUserProvider.php),
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
| [Login](#login)                              | `/musora-api/login`                                               |
| [Forgot password](#)                  | `/musora-api/forgot`                                              |
| [Change password](#)                        | `/musora-api/change-password`                                     |
| [Create Intercom user](#)                   | `/musora-api/intercom-user`                                       |

### Endpoints that require Authentication

|                                                          | Endpoints                                               |
|:-----------------------------------------------------------------|:----------------------------------------------------------|
| [Catalogues (filter contents)](#)        | `/musora-api/all`                                               |
| [In-progress lists](#)                  | `/musora-api/in-progress`                                              |
| [Pull content](#)                        | `/musora-api/content/{contentId}`                                     |
| [Search](#)                        | `/musora-api/search`                                     |
| [My lists](#)                        | `/musora-api/my-list`                                     |
|             &nbsp;      |                                   |
| [Packs list](#)        | `/musora-api/packs`                                               |
| [Get pack](#)                   | `/musora-api/pack/{packId}`                                       |
| [Get pack's lesson](#)        | `/musora-api/pack/lesson/{lessonId}`                                               |
| [Get next pack lesson](#)                  | `/musora-api/packs/jump-to-next-lesson/{packId}`                                              |
| &nbsp;            |                                              |
| [Leaning path](#)                        | `/musora-api/learning-paths/{learningPathSlug}`                                     |
| [Learning path level](#)                   | `/musora-api/learning-path-levels/{learningPathSlug}/{levelSlug}`                                       |
| [Leaning path course](#)                        | `/musora-api/learning-path-courses/{courseId}`                                     |
| [Learning path lesson](#)                   | `/musora-api/learning-path-lessons/{lessonId}`                                       |
|         &nbsp;         | &nbsp;                                     |
| [Schedule list](#)                        | `/musora-api/schedule`                                     |
| [Live schedule list](#)                   | `/musora-api/live-schedule`                                       |
| [Live event](#)                   | `/musora-api/live-event`                                       |
| &nbsp;                      | &nbsp;                                     |
| [Mark content as completed](#)                   | `/musora-api/complete`                                       |
| [Reset user content progress](#)                        | `/musora-api/reset`                                     |
| &nbsp;                      | &nbsp;                                     |
| [Track media](#)                   | `/musora-api/media`                                       |
| [Save video progress](#)                   | `/musora-api/media/{sessionId}`                                       |
| &nbsp;                      | &nbsp;                                     |
| [Submit question](#)                        | `/musora-api/submit-question`                                     |
| [Submit video](#)                   | `/musora-api/submit-video`                                       |
| [Submit student focus](#)                   | `/musora-api/submit-student-focus-form`                                       |
| &nbsp;                      | &nbsp;                                     |
| [Get authnticated user profile](#)                        | `/musora-api/profile`                                     |
| [Upload avatar](#)                   | `/musora-api/avatar/upload`                                       |
| [Update user profile](#)                   | `/musora-api/profile/update`                                       |
| &nbsp;                      | &nbsp; 
| [Add default lessons to user's list](#)                   | `/musora-api/add-lessons`                                       |


#Login

