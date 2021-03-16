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
| [Login](#login)                              | `/musora-api/login`                                               |
| [Forgot password](#forgot-password)                  | `/musora-api/forgot`                                              |
| [Change password](#change-password)                        | `/musora-api/change-password`                                     |
| [Create Intercom user](#create-Intercom-user)                   | `/musora-api/intercom-user`                                       |


### Endpoints that require Authentication

|                                                          | Endpoints                                               |
|:-----------------------------------------------------------------|:----------------------------------------------------------|
| [Catalogues (filter contents)](#filter-contents)        | `/musora-api/all`                                               |
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
| [Get authenticated user profile](#)                        | `/musora-api/profile`                                     |
| [Upload avatar](#)                   | `/musora-api/avatar/upload`                                       |
| [Update user profile](#)                   | `/musora-api/profile/update`                                       |
| &nbsp;                      | &nbsp;
| [Add default lessons to user's list](#)                   | `/musora-api/add-lessons`                                       |

## Login
Registered users can login to establish their identity with the application using the API below.

The `token` value returned from the login must be used in the subsequent requests to musora-api in order to maintain user session. 
The value uniquely identifies the user on the server and is used to enforce security policy, apply user and roles permissions and track usage analytics. 
For all requests made after the login, the token value must be sent in the HTTP header.

If `purchases` array exists on the request, the in-app purchases are automatically restored.

### HTTP Request

`PUT musora-api/api/login`

### Permissions

    - Without restrictions

### Request Parameters

|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|email|  yes  ||
|body|password|  yes  ||
|body|purchases|  no  |If purchases exist on the request, the in-app purchases are syncronized|

### Request Example:

```js

$.ajax({
    url: 'https://www.domain.com' +
        '/musora-api/login',
{
    "email":   "email@email.ro",
    "password":    "password", 
    "purchases":
    [
        {
            "purchase_token": "aaaababab",
            "product_id": "pianote_app_1_month_member",
            "package_name": "com.pianote2"
        }
    ]
}
,
success: function (response) {
}
,
error: function (response) {
}
})
;
```

### Response Example (200):

```json
{
  "success": true,
  "token": "eyJ0eX0YyJ9.ayJrvjNMrfDg78Aedglp6sEEoz6jzMLbHl7Gcy6Cygg",
  "isEdge": true,
  "isEdgeExpired": false,
  "edgeExpirationDate": null,
  "isPackOlyOwner": false,
  "tokenType": "bearer",
  "expiresIn": 3600,
  "userId": 1
}
```

### Response Example (401):
When the server-side reports an error, it returns a JSON object in the following format:
```json
{
  "success": false,
  "message": "Invalid Email or Password"
}
```


## Forgot password
An email is sent with a link to a webpage which contains a form where the user can enter the new password.

### HTTP Request
`PUT musora-api/forgot`


### Permissions
    - Without restrictions

### Request Parameters


|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|email|  yes  ||


### Request Example:

```js
$.ajax({
    url: 'https://www.domain.com' +
             '/musora-api/forgot',
{
    "email": "email@email.ro"
}
   ,
    success: function(response) {},
    error: function(response) {}
});
```

### Response Example (200):

```json
{
    "success": true,
    "title": "Please check your email",
    "message": "Follow the instructions sent to your email address to reset your password."
}
```

## Change password
The form where the user can enter the new password, using the link received after forgot password action.

### HTTP Request
`PUT musora-api/change-password`


### Permissions
    - Without restrictions

### Request Parameters


|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|rp-key|  yes  |Token provided in forgot password email|
|body|user_login|  yes  |User's email|
|body|pass1|  yes  |User's pass|


### Request Example:

```js
$.ajax({
    url: 'https://www.domain.com' +
             '/musora-api/change-password',
{
    "user_login": "email@email.ro",
    "rp_key": "accccc",
    "pass1": "new password"
}
   ,
    success: function(response) {},
    error: function(response) {}
});
```

### Response Example (200):

```json
{
  "success": true,
  "token": "eyJ0eX0YyJ9.ayJrvjNMrfDg78Aedglp6sEEoz6jzMLbHl7Gcy6Cygg",
  "isEdge": true,
  "isEdgeExpired": false,
  "edgeExpirationDate": null,
  "isPackOlyOwner": false,
  "tokenType": "bearer",
  "expiresIn": 3600,
  "userId": 1
}
```

### Response Example (500):
When the server-side reports an error, it returns a JSON object in the following format:
```json
{
  "success": false,
  "message": "Password reset failed, please try again."
}
```


## Forgot password
An email is sent with a link to a webpage which contains a form where the user can enter the new password.

### HTTP Request
`PUT musora-api/forgot`


### Permissions
    - Without restrictions

### Request Parameters


|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|email|  yes  ||


### Request Example:

```js
$.ajax({
    url: 'https://www.domain.com' +
             '/musora-api/forgot',
{
    "email": "email@email.ro"
}
   ,
    success: function(response) {},
    error: function(response) {}
});
```

### Response Example (200):

```json
{
    "success": true,
    "title": "Please check your email",
    "message": "Follow the instructions sent to your email address to reset your password."
}
```

## Create Intercom user
Create a new user in Intercom, with user's email address and tag `drumeo_started_app_signup_flow`


### HTTP Request
`PUT musora-api/intercom-user`


### Permissions
    - Without restrictions

### Request Parameters


|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|email|  yes  |User's email|


### Request Example:

```js
$.ajax({
    url: 'https://www.domain.com' +
             '/musora-api/intercom-user',
{
    "email": "email@email.ro"
}
   ,
    success: function(response) {},
    error: function(response) {}
});
```

### Response Example (200):

```json
{
  "success": true,
}
```

### Response Example (500):
When the server-side reports an error, it returns a JSON object in the following format:
```json
{
  "success": false,
  "message": "Intercom exception when create intercom user. Message:message text"
}
```
## Filter contents
Get an array with contents data that respect filters criteria. 
The results are paginated and respect the response structure defined in musora-api config file.

### HTTP Request
`GET musora-api/all`


### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d)

### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  sort                             |  no       |  'published_on'  |  Defaults to ascending order; to switch to descending order put a minus sign (-) in front of the value. Can be any of the following: slug; status; type; brand; language; position; parent_id; published_on; created_on; archived_on and progress                                           |
| query           |  included_types                   |  no       |  []              |  Only contents with these types will be returned.                                                                                                                                                                                                                                    |
| query           |  statuses                         |  no       |  'published'     |  All content must have one of these statuses.                                                                                                                                                                                                                                   |
| query           |  filter[required_fields]          |  no       |  []              |  All returned contents are required to have this field. Value format is: key;value;type (type is optional if its not declared all types will be included)                                                                                                                       |
| query           |  filter[included_fields]          |  no       |  []              |  Contents that have any of these fields will be returned. The first included field is the same as a required field but all included fields after the first act inclusively. Value format is: key value type (type is optional - if its not declared all types will be included) |
| query           |  filter[required_user_states]     |  no       |  []              |  All returned contents are required to have these states for the authenticated user. Value format is: state                                                                                                                                                                     |
| query           |  filter[included_user_states]     |  no       |  []              |  Contents that have any of these states for the authenticated user will be returned. The first included user state is the same as a required user state but all included states after the first act inclusively. Value format is: state.                                        |



### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/all?' +
        'page=1' + '&' +
        'limit=1' + '&' +
        'included_types[]=course' + '&' +
        'statuses[]=published' + '&' +
        'filter[required_fields][]=topic,rock,string' + '&' +
        'filter[included_fields][]=topic,jazz,string' + '&' +
        'filter[included_fields][]=difficulty,3,integer' + '&' +
        'filter[included_fields][]=difficulty,9' + '&' +
        'filter[required_user_states][]=completed' + '&' +
        'filter[included_user_states][]=started',
    type: 'get',
    dataType: 'json',
    success: function(response) {
        // handle success
    },
    error: function(response) {
        // handle error
    }
});
```

### Response Example (200):

```json
{
  "data": [
    {
      "id": 285555,
      "thumbnail_url": null,
      "type": "play-along",
      "published_on": "2021/03/13 16:00:00",
      "status": "published",
      "title": "Broadway Hustle",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Rob Brown"
      ],
      "artist": "Rob Brown",
      "style": "Pop/Rock",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    },
    {
      "id": 285554,
      "thumbnail_url": null,
      "type": "performances",
      "published_on": "2021/03/13 16:00:00",
      "status": "published",
      "title": "Rob Brown - \"Broadway Hustle\"",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Rob Brown"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    }
  ],
  "meta": {
    "totalResults": 5688,
    "page": "1",
    "limit": "2",
    "filterOptions": {
      "content_type": [
        "25-days-of-christmas",
        "behind-the-scenes",
        "boot-camps",
        "camp-drumeo-ah",
        "challenges",
        "coach-stream",
        "course",
        "diy-drum-experiments",
        "exploring-beats",
        "gear-guides",
        "in-rhythm",
        "learning-path",
        "live",
        "namm-2019",
        "on-the-road",
        "pack",
        "paiste-cymbals",
        "performances",
        "play-along",
        "podcasts",
        "question-and-answer",
        "quick-tips",
        "rhythmic-adventures-of-captain-carson",
        "rhythms-from-another-planet",
        "semester-pack",
        "solos",
        "song",
        "sonor-drums",
        "student-collaborations",
        "student-focus",
        "study-the-greats",
        "tama-drums"
      ],
      "topic": [
        "All",
        "Alternative",
        "Beats",
        "Diddle Rudiments",
        "Ear Training",
        "Edutainment",
        "Fills",
        "Funk",
        "Gear",
        "Gear Talk",
        "Hand Technique",
        "Independence",
        "Jazz",
        "Latin",
        "Leader",
        "Metal",
        "Musicality",
        "Odd Time",
        "Performances",
        "Podcasts",
        "Question & Answer",
        "Rudiments",
        "Solos",
        "Styles",
        "Technique",
        "Theory"
      ],
      "style": [
        "All",
        "-",
        "Alternative",
        "Beats",
        "Blues",
        "Bues",
        "CCM/Worship",
        "Country",
        "Electronic",
        "Funk",
        "Funk. Electronic",
        "Funk. Odd Time",
        "Hip-Hop/Rap",
        "Jazz",
        "Latin",
        "Metal",
        "Odd Time",
        "Odd-Time",
        "Pop/Rock",
        "Pop/Rock,Blues",
        "Pop/Rock/Metal",
        "R&B",
        "R&B Electronic",
        "R&B/Soul",
        "Rock/Pop",
        "Swing",
        "World"
      ],
      "difficulty": [
      "All Skill Levels",
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "10"
      ],
      "instructor": [
        {
          "id": 31888,
          "name": "Aaron Edgar",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/aaron-edgar.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 236679,
          "name": "Aaron Gillespie",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/236679-avatar-1573484038.jpg",
          "type": "instructor"
        },
        {
          "id": 267122,
          "name": "Yoni Madar",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/267122-avatar-1598445518.jpg",
          "type": "instructor"
        }
      ],
      "showSkillLevel": true
    }
  }
}
```


## Pull in-progress contents

Get an array with content in progress that respect filters criteria.
The results are paginated and respect the response structure defined in musora-api config file.

### HTTP Request
`GET musora-api/in-progress`


### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-4c4fdfd3-fd0d-4c94-94bd-40c2fa218ffa)

### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  sort                             |  no       |  'published_on'  |  Defaults to ascending order; to switch to descending order put a minus sign (-) in front of the value. Can be any of the following: slug; status; type; brand; language; position; parent_id; published_on; created_on; archived_on and progress                                           |
| query           |  included_types                   |  no       |  []              |  Only contents with these types will be returned.                                                                                                                                                                                                                                    |
| query           |  statuses                         |  no       |  'published'     |  All content must have one of these statuses.                                                                                                                                                                                                                                   |
| query           |  filter[required_fields]          |  no       |  []              |  All returned contents are required to have this field. Value format is: key;value;type (type is optional if its not declared all types will be included)                                                                                                                       |
| query           |  filter[included_fields]          |  no       |  []              |  Contents that have any of these fields will be returned. The first included field is the same as a required field but all included fields after the first act inclusively. Value format is: key value type (type is optional - if its not declared all types will be included) |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/in-progress?' +
        'page=1' + '&' +
        'limit=10' + '&' +
        'included_types[]=course' + '&' +
        'statuses[]=published' + '&' +
        'filter[required_fields][]=topic,rock,string' + '&' +
        'filter[included_fields][]=topic,jazz,string' + '&' +
        'filter[included_fields][]=difficulty,3,integer' + '&' +
        'filter[included_fields][]=difficulty,9',
    type: 'get',
    dataType: 'json',
    success: function(response) {
        // handle success
    },
    error: function(response) {
        // handle error
    }
});
```

### Response Example (200):

```json
{
  "data": [
    {
      "id": 287865,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/287865-card-thumbnail-1614860513.png",
      "type": "quick-tips",
      "published_on": "2021/03/04 18:00:00",
      "status": "published",
      "title": "Drum Chops For Beginners",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "957",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    },
    {
      "id": 287342,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/287342-card-thumbnail-1614637391.png",
      "type": "question-and-answer",
      "published_on": "2021/02/23 01:00:00",
      "status": "published",
      "title": "Weekly Q&A",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Aaron Edgar"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "4283",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false
    }
  ],
  "meta": {
    "totalResults": 27,
    "page": "1",
    "limit": "2",
    "filterOptions": {
      "content_type": [
        "course",
        "pack-bundle-lesson",
        "podcasts",
        "question-and-answer",
        "quick-tips"
      ],
      "difficulty": [
        "All",
        "1",
        "10",
        "4",
        "5",
        "7",
        "All Skill Levels"
      ],
      "topic": [
        "All",
        "Beats",
        "Ear Training",
        "Edutainment",
        "Fills",
        "Gear",
        "Musicality",
        "Rudiments",
        "Styles",
        "Theory"
      ],
      "style": [
        "All"
      ],
      "instructor": [
        {
          "id": 31888,
          "name": "Aaron Edgar",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/aaron-edgar.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 273265,
          "name": "Rob Brown",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/273265-avatar-1605005582.png",
          "type": "instructor"
        },
        {
          "id": 206503,
          "name": "Seamus Evely",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/seamus-evely.jpg",
          "type": "instructor"
        }
      ],
      "showSkillLevel": true
    }
  }
}
```

