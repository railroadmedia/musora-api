## Leaving

Get content to be removed in the next quarter (set to unlisted)

### HTTP Request
`GET musora-api/v1/content-updates/leaving`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key                              |  required   |  default |  description                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|--------------------------------------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------| 
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                                                                                                                                                  
### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/v1/content-updates/leaving',
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
      "id": 412282,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/coldplay-parachutes-1724688598.jpg",
      "sheet_music_thumbnail_url": null,
      "difficulty_string": "Intermediate",
      "difficulty": "5",
      "type": "song",
      "slug": "don-t-panic-solo",
      "instrumentless": 1,
      "published_on": "2024/08/29 15:00:00",
      "status": "unlisted",
      "title": "Don't Panic (Solo)",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [],
      "artist": "Coldplay",
      "style": [
        "Pop"
      ],
      "topic": [],
      "length_in_seconds": null,
      "name": null,
      "head_shot_picture_url": null,
      "is_liked_by_current_user": false,
      "like_count": 0,
      "description": null,
      "high_soundslice_slug": null,
      "low_soundslice_slug": null,
      "live_event_start_time": null,
      "live_event_end_time": null,
      "published_on_in_timezone": "2024/08/29 08:00:00",
      "guitar_chord_image_url": null,
      "need_access": false
    }
  ],
  "meta": {
    "totalResults": 1,
    "page": 1,
    "limit": 10,
    "filterOptions": {
      "style": [
        "All",
        "Pop"
      ],
      "topic": [
        "All"
      ]
    }
  }
}
```
