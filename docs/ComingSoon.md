## Coming Soon

Get the next N Songs that will be released. Content that is marked as published in the future

### HTTP Request
`GET musora-api/v1/content-updates/coming-soon`


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
        '/musora-api/v1/content-updates/coming-soon',
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
      "id": 412286,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/coldplay-xandy-1724689023.jpg",
      "sheet_music_thumbnail_url": null,
      "difficulty_string": "Beginner",
      "difficulty": "3",
      "type": "song",
      "slug": "the-hardest-part-easy-version",
      "instrumentless": 0,
      "published_on": "2024/08/30 09:15:00",
      "status": "published",
      "title": "The Hardest Part (Easy Version)",
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
      "published_on_in_timezone": "2024/08/30 02:15:00",
      "guitar_chord_image_url": null,
      "need_access": false
    }
  ],
  "meta": {
    "totalResults": 550,
    "page": 1,
    "limit": 2,
    "filterOptions": {
      "lifestyle": [
        "All"
      ],
      "difficulty": [
        "All",
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
      "showSkillLevel": true
    }
  }
}
```
