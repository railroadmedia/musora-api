## Add default content in user's playlist

Add lessons in user's playlst based on user's topics and skill level.
The mapping between skill, topics and contents is defined in [lessonsSkillsAndTopicMapping](https://github.com/railroadmedia/drumeo/blob/master/laravel/config/lessonsSkillsAndTopicMapping.php) config file

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-02e7bbcd-0a7f-42fd-b666-f497b26da66f"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request

`PUT musora-api/add-lessons`

### Permissions

    - Only authenticated user

|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|skill|  no  | User's skill level|
|body|topics|  no  | Array with user topics|


### Request Example:

```js

$.ajax({
    url: 'https://www.domain.com' +
        '/musora-api/add-lessons',
    type: 'put',
    dataType: 'json',
    data:{
        "skill":"beginner",
        "topics":["drumFills","drumBeats"]
    },
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
      "id": 31086,
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/courses/550/dcb-41.jpg",
      "type": "course",
      "published_on": "2017/10/07 10:00:35",
      "status": "published",
      "title": "The Rock Beat Formula",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": true,
      "instructors": [
        "Randy Cooke"
      ],
      "artist": null,
      "style": "Pop/Rock",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    },
    {
      "id": 24804,
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/courses/550/dci-63.jpg",
      "type": "course",
      "published_on": "2015/11/25 15:00:37",
      "status": "published",
      "title": "Useful Grooves Drummers Should Know",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": true,
      "instructors": [
        "Rich Redmond"
      ],
      "artist": null,
      "style": "All",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    },
    {
      "id": 20977,
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/courses/550/dcb-01.jpg",
      "type": "course",
      "published_on": "2014/03/30 18:37:05",
      "status": "published",
      "title": "Getting Started On Drums",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": true,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": "All",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    }
  ],
  "meta": {
    "totalResults": 3,
    "page": 1,
    "limit": 10,
    "filterOptions": {
      "showSkillLevel": true
    }
  }
}
```

