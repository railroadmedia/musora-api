## Next lesson based on user progress for course/method/pack

The endpoint is available only for v1 endpoints. The brand should be specified as a parameter.
It returns a JSON response with next lesson based on user progress.

<a href="https://red-shadow-611407.postman.co/workspace/Team-Workspace~38bb093f-0978-4a83-8423-944a3c78fd51/example/9725390-66b54d42-82b8-45ad-aebb-597a3e907ce2"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/v1/jump-to-continue-content/{parentId}`

### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters
| path\|query\|body|  key                |  required |  description           |
|------------------|---------------------|-----------|------------------------|
| path            |  parentId  |  yes      |  The id of the course/method/level/course/pack.


### Request Example:

```js
$.ajax({
    url: 'https://app-staging-one.musora.com/' +
        '/musora-api/v1/jump-to-continue-content/{parentId}',
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
  "id": 280601,
  "type": "pack-bundle-lesson",
  "title": "Intro",
  "description": "Welcome! In this first lesson we're going to get you comfortable holding, strumming, fretting and overall playing your guitar. By the end of this lesson you'll have the skill to play two different chords, perform a full song using them and even begin to write your own music.",
  "completed": false,
  "started": false,
  "progress_percent": 0,
  "vimeo_video_id": "489918210",
  "youtube_video_id": null,
  "length_in_seconds": 10,
  "xp": 150,
  "total_xp": "150",
  "xp_bonus": 150,
  "published_on": "2020/12/31 17:00:00",
  "is_added_to_primary_playlist": false,
  "like_count": 172
}
```
