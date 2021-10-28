## Subscribed Coaches
Get an array with the subscribed/followed coaches 
The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/followed-content?content_type=coach&brand={brand}&limit={limit}&page={page}`


### Permissions
    - Only authenticated user can access the endpoint


### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  content_type                     |  no       |                  |  Only followed contents with this type will be returned.                                                                                                                                                                                                                                    |
| query           |  brand                            |  no       |  config default value              |  Only contents with the brand will be returned.                                                                                                                                                                                                                                    |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/followed-content?' +
        'page=1' + '&' +
        'limit=1' + '&' +
        'content_type=coach' ,
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
      "like_count": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/api/content/281910",
      "live_event_start_time": null,
      "live_event_end_time": null
    }
  ],
  "meta": {
    "totalResults": 1,
    "page": 1,
    "limit": 10,
    "filterOptions": {
      "showSkillLevel": true
    }
  }
}
```
