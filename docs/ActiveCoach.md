## Featured Coaches
Get an array with the active coaches 
The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/all?brand={brand}&limit={limit}&statuses[]={status}&required_fields[]=is_active,1&included_types[]=coach&sort=-published_on&page={page}`


### Permissions
    - Only authenticated user can access the endpoint


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
        'included_types[]=coach' + '&' +
        'required_fields[]=active,1',
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
      "id": 281906,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281906-card-thumbnail-1624382173.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Aric Improta",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Aric Improta",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281906-avatar-1609277595.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281906",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281901,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281901-card-thumbnail-1624382066.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Sarah Thawer",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Sarah Thawer",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281901-avatar-1609277471.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281901",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281907,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281907-card-thumbnail-1624382158.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "John Wooton",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "John Wooton",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281907-avatar-1609277568.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281907",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281909,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281909-card-thumbnail-1624382127.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Kaz Rodriguez",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Kaz Rodriguez",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281909-avatar-1609277511.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281909",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281902,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281902-card-thumbnail-1624382467.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Todd Sucherman",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Todd Sucherman",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281902-avatar-1609535955.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281902",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281910,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281910-card-thumbnail-1624382098.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Jared Falk",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Jared Falk",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281910-avatar-1609277499.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281910",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281903,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281903-card-thumbnail-1624382211.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Domino Santantonio",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Domino Santantonio",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281903-avatar-1609277662.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281903",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281911,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281911-card-thumbnail-1624382247.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Matt McGuire",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Matt McGuire",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281911-avatar-1609277722.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281911",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281904,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281904-card-thumbnail-1624382199.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Larnell Lewis",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Larnell Lewis",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281904-avatar-1609277645.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281904",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 281905,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281905-card-thumbnail-1624382185.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Dorothea Taylor",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Dorothea Taylor",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281905-avatar-1609277433.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281905",
      "live_event_start_time": null,
      "live_event_end_time": null
    }
  ],
  "meta": {
    "totalResults": 11,
    "page": 1,
    "limit": 10,
    "filterOptions": {
      "showSkillLevel": true
    }
  }
}
```
