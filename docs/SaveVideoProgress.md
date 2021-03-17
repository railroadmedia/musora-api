## Track user's media playback

Store in the database information about user progress on video: how many seconds the user watched.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-35138923-5c9d-4c79-9869-2bcb85625824" style="float:right;" target="_blank">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>


### HTTP Request
`PUT musora-api/reset/{sessonId}`


### Permissions
    - Only authenticated user can access the endpoint






### Request Parameters

| path\|query\|body|  key                |  required | default | description           |
|------------------|---------------------|-----------|--------------|--------------------|
| path            |  session_id  |  yes      |  | The session id received from track media endpoint.
| body            |  seconds_played  |  no  |     |  Where the user currently is in the video.
| body            |  current_second  |  no      |  0  |Where the user currently is in the video.


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/media/519168333',
    type: 'put',
    dataType: 'json',
    data:{
        "seconds_played":"22.001291275024414",
        "current_second":"22.001291275024414"
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
  "success": true,
  "session_id": "519168333"
}
```