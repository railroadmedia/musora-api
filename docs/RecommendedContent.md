## Recommended Content
Get an array with the user's subscribed coaches latest content or all coaches latest content if the user is not subscribed to any coaches
The results are paginated and respect the response structure defined in musora-api config file.
<!---
<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>
--->

### HTTP Request
`GET musora-api/recommended?brand={brand}&limit={limit}&page={page}`


### Permissions
    - Only authenticated user can access the endpoint


### Request Parameters

| path\|query\|body | key       | required | default              | description\|notes                                                                                                                                                                                                                                                                                   |
|-------------------|-----------|----------|----------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query or body     | brand     | yes      | config default value | Only contents with the brand will be returned.                                                                                                                                                                                                                                                       |
| query or body     | filter    | no       | ''                   | High level filter. Supported non-default values are: songs, lessons                                                                                                                                                                                                                                  |
| query or body     | limit     | no       | 10                   | The max amount of contents that can be returned. (Page size)                                                                                                                                                                                                                                         |
| query or body     | page      | no       | 1                    | Page of results to return. This parameter is overwritten by the randomize flag                                                                                                                                                                                                                       |
| query or body     | randomize | no       | 0                    | Passing a truthsy value will randomize the elements returned from the full list (but not the order). The current implementation of this updates the randomized content every hour (ie: calling it repeatedly within the hour will return the same results). This value overrides the page parameter. |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/recommended?' +
        'brand=drumeo' + '&' +
        'limit=6' + '&' +
        'randomize=1' + '&' +
        'filter=song', 
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
      "id": 397032,
      "brand": "drumeo",
      "popularity": 1248,
      "difficulty_string": "Novice",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/Fills-PackCards-WithLogo-1695855950.jpg",
      "type": "course",
      "published_on": "2023/10/05 07:01:00",
      "status": "published",
      "title": "10-Day Fills",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Domino Santantonio"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "name": null,
      "head_shot_picture_url": null,
      "is_liked_by_current_user": false,
      "like_count": 3,
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "is_active": null,
      "is_featured": 1,
      "focus_text": null,
      "banner_background_image_url": null,
      "forum_thread_url": null,
      "guitar_chord_image_url": null,
      "lesson_count": 10
    },
    {
      "id": 402069,
      "brand": "drumeo",
      "popularity": 6300,
      "difficulty_string": "Beginner",
      "thumbnail_url": "https://musora-web-platform.s3.amazonaws.com/workouts/drumeo/pat-boone-debby-boone-variations.jpg",
      "type": "workout",
      "published_on": "2023/12/28 00:58:00",
      "status": "published",
      "title": "Pat Boone Debby Boone Variations",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Brandon Toews"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": 669,
      "name": null,
      "head_shot_picture_url": null,
      "is_liked_by_current_user": false,
      "like_count": 170,
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "banner_background_image_url": null,
      "forum_thread_url": null,
      "guitar_chord_image_url": null
    },
    {
      "id": 397582,
      "brand": "drumeo",
      "popularity": 743,
      "difficulty_string": "All",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/NitroMaxE-Kit-01-1696071401.jpg",
      "type": "course",
      "published_on": "2023/10/03 13:00:00",
      "status": "published",
      "title": "How To Use Your Nitro Max E-Kit",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Brandon Toews"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "name": null,
      "head_shot_picture_url": null,
      "is_liked_by_current_user": false,
      "like_count": 0,
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "is_active": null,
      "is_featured": "0",
      "focus_text": null,
      "banner_background_image_url": null,
      "forum_thread_url": null,
      "guitar_chord_image_url": null,
      "lesson_count": 4
    },
    {
      "id": 240154,
      "brand": "drumeo",
      "popularity": 12,
      "difficulty_string": "Beginner",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/240154-card-thumbnail-1620228904.jpg",
      "type": "course",
      "published_on": "2019/12/28 16:00:00",
      "status": "published",
      "title": "Beginner Drum Fills",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Mike Michalkow"
      ],
      "artist": null,
      "style": "Pop/Rock",
      "length_in_seconds": null,
      "name": null,
      "head_shot_picture_url": null,
      "is_liked_by_current_user": false,
      "like_count": 0,
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "is_active": null,
      "is_featured": null,
      "focus_text": null,
      "banner_background_image_url": null,
      "forum_thread_url": null,
      "guitar_chord_image_url": null,
      "lesson_count": 5
    },
    {
      "id": 396948,
      "brand": "drumeo",
      "popularity": 44,
      "difficulty_string": "Advanced",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/1-Member-Thumb-1920x1080(47)-1695649358.jpg",
      "type": "quick-tips",
      "published_on": "2023/09/30 00:00:00",
      "status": "published",
      "title": "The Genius Of Keith Moon",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Brandon Toews"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "1718",
      "name": null,
      "head_shot_picture_url": null,
      "is_liked_by_current_user": false,
      "like_count": 79,
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [],
      "genre": [],
      "is_active": null,
      "is_featured": 1,
      "focus_text": null,
      "banner_background_image_url": null,
      "forum_thread_url": null,
      "guitar_chord_image_url": null
    }
  ],
  "meta": {
    "totalResults": 5,
    "page": 1,
    "limit": "5",
    "filterOptions": {
      "style": [
        "All"
      ],
      "topic": [
        "All"
      ],
      "artist": [
        "All"
      ]
    }
  }
}
```
