## Pull current live event or next upcoming event

Return the current, or the next live event based on the event start date. The next live event is returned only if there is an event within the next X minutes. (X - is set in website config file; by default: 240 min).

For testing purpose (a preview for the event) the `forced-content-id` request parameter can be used.

### HTTP Request
`GET musora-api/live-event`


### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-35138923-5c9d-4c79-9869-2bcb85625824)

### Request Parameters

| path\|query\|body|  key                |  required |  description           |
|------------------|---------------------|-----------|------------------------|
| query            |  forced-content-id  |  no       |  For testing purpose.
| query            |  timezone           |  no       |  Used to return live event based on user timezone                 |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/live-event',
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
    "id": 292762,
    "type": "coach-stream",
    "isLive": false,
    "title": "Your Q&A With Shcack",
    "live_event_start_time": "2021/03/10 20:00:00",
    "live_event_end_time": "2021/03/10 21:00:00",
    "is_added_to_primary_playlist": false,
    "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292762-card-thumbnail-1614107005.png",
    "instructors": [
        "Michael Schack"
    ],
    "chatRollEmbedUrl": "https://chatroll.com/embed/chat/drumeo-dev-chat?id=enabv2muCjJ&platform=php&uid=149628&uname=Roxana+R&ulink=https://staging.drumeo.com/laravel/public/members/profile/149628&upic=https://dzryyo1we6bm3.cloudfront.net/avatars/Screenshot_20210223-123705-1614849346-149628.jpg&ismod=1&sig=51c2f9a2cbc5a10a86be8cc68c602d78",
    "chatRollViewersNumberClass": ".chat-online-count"
}
```