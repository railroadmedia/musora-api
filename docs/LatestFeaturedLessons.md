## Latest featured lessons
Get an array with the latest lessons from all the featured coaches.
The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/featured-lessons?limit={limit}&page={page}`


### Permissions
    - Only authenticated user can access the endpoint


### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  included_types                   |  no       |                  |  Only featured lessons with this type will be returned.                                                                                                                                                                                                                                    |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/featured-lessons?' +
        'page=1' + '&' +
        'limit=1' + '&',
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
      "id": 324647,
      "popularity": null,
      "thumbnail_url": null,
      "type": "play-along",
      "published_on": "2021/11/07 16:00:00",
      "status": "published",
      "title": "Crosswalk Shuffle",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": "Chris Fischer",
      "style": "Funk",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324647",
      "musora_api_mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324647",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 324646,
      "popularity": null,
      "thumbnail_url": null,
      "type": "performances",
      "published_on": "2021/11/07 16:00:00",
      "status": "published",
      "title": "Chris Fischer - \"Crosswalk Shuffle\"",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324646",
      "musora_api_mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324646",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 323256,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/coach-mm-playing-iconic-songs-1635779052.png",
      "type": "coach-stream",
      "published_on": "2021/11/01 23:00:00",
      "status": "published",
      "title": "Playing Iconic Songs",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Matt McGuire"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false,
      "live_event_start_time": "2021/11/01 23:00:00",
      "live_event_end_time": "2021/11/02 00:15:00",
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 324643,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/PA-these-go-to-eleven-1635504558.png",
      "type": "play-along",
      "published_on": "2021/10/31 15:00:00",
      "status": "published",
      "title": "These Go To Eleven",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": "Chris Fischer",
      "style": "Funk",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324643",
      "musora_api_mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324643",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 324640,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/perf-JF-these-go-to-eleven-1635504428.png",
      "type": "performances",
      "published_on": "2021/10/31 15:00:00",
      "status": "published",
      "title": "Chris Fischer - \"These Go To Eleven\"",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324640",
      "musora_api_mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/324640",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 323255,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/coach-mm-hand-foot-combos-1635171874.png",
      "type": "coach-stream",
      "published_on": "2021/10/25 19:00:00",
      "status": "published",
      "title": "Hand & Foot Combos",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Matt McGuire"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false,
      "live_event_start_time": "2021/10/25 19:00:00",
      "live_event_end_time": "2021/10/25 20:15:00",
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 323805,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/PA-don't-blink-1634642077.png",
      "type": "play-along",
      "published_on": "2021/10/24 15:00:00",
      "status": "published",
      "title": "Don't Blink",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": "Chris Fischer",
      "style": "Electronic",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/323805",
      "musora_api_mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/323805",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 323804,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/perf-JF-don't-blink-1634641590.png",
      "type": "performances",
      "published_on": "2021/10/24 15:00:00",
      "status": "published",
      "title": "Chris Fischer - \"Don't Blink\"",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/323804",
      "musora_api_mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/323804",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 323801,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/qt-EC-fundamentals-traditional-grip-1634639048.png",
      "type": "quick-tips",
      "published_on": "2021/10/23 15:00:00",
      "status": "published",
      "title": "The Fundamentals Of Traditional Grip",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Emmanuelle Caplette"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/323801",
      "musora_api_mobile_app_url": "https://dev.drumeo.com/laravel/public/musora-api/content/323801",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    },
    {
      "id": 323140,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/coach-mm-what-to-wear-drumming-1634571104.png",
      "type": "coach-stream",
      "published_on": "2021/10/18 19:00:00",
      "status": "published",
      "title": "What To Wear - Dressing For Drumming",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Matt McGuire"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false,
      "live_event_start_time": "2021/10/18 19:00:00",
      "live_event_end_time": "2021/10/18 20:15:00",
      "focus": [],
      "genre": [],
      "vimeo_video_id": null,
      "youtube_video_id": null,
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "short_bio": null,
      "card_thumbnail_url": null,
      "banner_background_image_url": null,
      "bio_image": null,
      "long_bio": null,
      "forum_thread_url": null
    }
  ],
  "meta": {
    "totalResults": 676,
    "page": 1,
    "limit": 10,
    "filterOptions": {
      "showSkillLevel": true
    }
  }
}
```
