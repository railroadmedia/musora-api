## Track user's media session

Store in the database information about the media session in the database amd return the session id.  

### HTTP Request
`PUT musora-api/reset`


### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-35138923-5c9d-4c79-9869-2bcb85625824)

### Request Parameters

| path\|query\|body|  key                |  required | default | description           |
|------------------|---------------------|-----------|--------------|--------------------|
| body            |  media_type  |  no      |  'video'  | Media type
| body            |  media_category  |  no  | 'vimeo'    |  Video category: `vimeo` or `youtube`.
| body            |  media_id  |  no      |  'video'  |The vimeo video id or youtube video id.
| body            |  media_length_seconds  |  no  |     |  The video length in seconds
| body            |  current_second  |  no      |  0  |Where the user currently is in the video.


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/media',
    type: 'put',
    dataType: 'json',
    data:{
        "media_type":"video",
        "media_category":"vimeo",
        "media_id":"519168333",
        "content_id":287865,
        "media_length_seconds":957
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
  "session_id": {
    "uuid": "0788c086-a3c7-4dde-97e4-90c4b34a7ec9",
    "media_id": "519168333",
    "media_length_seconds": 957,
    "user_id": 149628,
    "type_id": "6",
    "seconds_played": 0,
    "current_second": 0,
    "started_on": "2021-03-17 11:35:29",
    "last_updated_on": "2021-03-17 11:35:29",
    "id": 4719534
  }
}
```