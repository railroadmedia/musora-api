## Pull current live event or next upcoming event

Return the current, or the next live event based on the event start date. The next live event is returned only if there is an event within the next X minutes. (X - is set in website config file; by default: 240 min).

For testing purpose (a preview for the live event or upcoming event) the `forced-content-id`/ `forced-upcoming-content-id` request parameter can be used. 

In the endpoint's response structure, `isLive` will be true if there is an live event and false if there is an upcoming event.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-35138923-5c9d-4c79-9869-2bcb85625824"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/live-event`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key                |  required |  description           |
|------------------|---------------------|-----------|------------------------|
| body            |  forced-content-id  |  no       |  Used to preview a live event.
| body            |  forced-upcoming-content-id  |  no       |  Used to preview an upcoming event.
| body            |  timezone           |  no       |  Used to return live event based on user timezone                 |


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
    "id": 290983,
    "type": "question-and-answer",
    "isLive": true,
    "title": "What's The Purpose Of A Scale?",
    "live_event_start_time": "2021/02/25 22:00:00",
    "live_event_end_time": "2021/02/25 23:00:00",
    "live_event_start_time_in_timezone": "2021/02/25 14:00:00",
    "live_event_end_time_in_timezone": "2021/02/25 15:00:00",
    "is_added_to_primary_playlist": false,
    "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/290983-card-thumbnail-1614112375.png",
    "instructors": [
        "Lisa Witt"
    ],
    "chatRollEmbedUrl": "https://chatroll.com/embed/chat/drumeo-dev-chat?id=enabv2muCjJ&platform=php&uid=149628&uname=Roxana+R&upic=https%3A%2F%2Fdzryyo1we6bm3.cloudfront.net%2Favatars%2FScreenshot_20210223-123705-1614849346-149628.jpg&ismod=1&sig=51c2f9a2cbc5a10a86be8cc68c602d78",
    "chatRollViewersNumberClass": ".chat-online-count",
    "youtube_video_id": "36YnV9STBqc",
    "chatRollStyle": {
        ".chat-popout-button": {
            "display": "none"
        },
        ".chat-contacts-banner": {
            "display": "none"
        },
        ".chat-welcome-message": {
            "display": "none"
        },
        ".message-content": {
            "padding": "5px",
            "align-items": "center"
        },
        ".chat-input-wrapper": {
            "top": 0,
            "padding": "10px",
            "padding-left": 0
        },
        ".chat-signin-box": {
            "background": {
                "dark": "#00101D",
                "light": "#F7F9FC"
            }
        },
        ".profile-icon-image": {
            "border": "1px solid",
            "border-color": {
                "dark": "#445F74",
                "light": "#00101D"
            }
        },
        ".chat-wrapper": {
            "display": "flex",
            "flex-direction": "column",
            "background": {
                "dark": "#00101D",
                "light": "#F7F9FC"
            }
        },
        ".message-text": {
            "font-weigh": 400,
            "font-size": "12px",
            "color": {
                "dark": "#EDEEEF",
                "light": "#00101D"
            }
        },
        ".message-profile-name": {
            "font-weight": 400,
            "font-size": "12px",
            "margin-right": "5px",
            "color": {
                "dark": "#97AABE",
                "light": "#445F74"
            }
        },
        ".message-profile-image": {
            "top": 0,
            "left": 0,
            "padding": 0,
            "border": "none",
            "position": "relative"
        },
        ".chat-messages-container": {
            "top": 0,
            "padding": 0,
            "overflow": "unset",
            "position": "relative",
            "padding-bottom": "60px",
            "background": {
                "dark": "#00101D",
                "light": "#F7F9FC"
            }
        },
        ".message": {
            "font-size": 0,
            "padding": "10px",
            "display": "flex",
            "align-items": "center",
            "justify-content": "flex-end",
            "flex-direction": "row-reverse"
        },
        ".chat-input-container": {
            "bottom": 0,
            "padding": 0,
            "border": "none",
            "display": "flex",
            "position": "fixed",
            "background": "#0b0b0b"
        },
        "textarea": {
            "color": "white",
            "border": "none",
            "height": "40px",
            "padding": "5px",
            "background": "#242527",
            "border-radius": "10px"
        }
    }
}
```
