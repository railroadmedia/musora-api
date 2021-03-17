## Track user's media playback

Store in the database information about user progress on video: how many seconds the user watched.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-35138923-5c9d-4c79-9869-2bcb85625824">
<button style="float:right; background-color:#0b76db;height:35px;width:150px;color:#fff;font-family:Roboto Condensed;font-weight:700;">
Try it with Postman!
</button>
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