## Live Schedule contents
Get live scheduled contents data .
The results respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-797296e0-3f73-44b9-8a09-0b50dbd0c69a"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/live-schedule`

### Permissions
    - Only authenticated user can access the endpoint


## Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/live-schedule',
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
[
    {
        "id": 292723,
        "type": "coach-stream",
        "title": "Exploring Genres",
        "live_event_start_time": "2021/03/15 17:00:00",
        "live_event_end_time": "2021/03/15 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292723-card-thumbnail-1614106332.png",
        "published_on": "2021/03/15 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292735,
        "type": "coach-stream",
        "title": "Exploring Different Grips and Strokes",
        "live_event_start_time": "2021/03/16 16:00:00",
        "live_event_end_time": "2021/03/16 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292735-card-thumbnail-1614106473.png",
        "published_on": "2021/03/16 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 293040,
        "type": "coach-stream",
        "title": "Drum Workout Session: Fills And Drills",
        "live_event_start_time": "2021/03/16 19:30:00",
        "live_event_end_time": "2021/03/16 20:30:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/293040-card-thumbnail-1614283521.png",
        "published_on": "2021/03/16 19:30:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Larnell Lewis"
        ],
        "isLive": false
    },
    {
        "id": 292752,
        "type": "coach-stream",
        "title": "Learn My Version Of “Levitating” By Dua Lipa Ft. DaBaby",
        "live_event_start_time": "2021/03/16 22:00:00",
        "live_event_end_time": "2021/03/16 23:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292752-card-thumbnail-1614106749.png",
        "published_on": "2021/03/16 22:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Domino Santantonio"
        ],
        "isLive": false
    },
    {
        "id": 293672,
        "type": "student-focus",
        "title": "Student Focus",
        "live_event_start_time": "2021/03/17 17:00:00",
        "live_event_end_time": "2021/03/17 18:00:00",
        "thumbnail_url": null,
        "published_on": "2021/03/17 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kyle Radomsky"
        ],
        "isLive": false
    },
    {
        "id": 292763,
        "type": "coach-stream",
        "title": "The Best 40 Mins Of The Day",
        "live_event_start_time": "2021/03/17 19:00:00",
        "live_event_end_time": "2021/03/17 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292763-card-thumbnail-1614107019.png",
        "published_on": "2021/03/17 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292780,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #10",
        "live_event_start_time": "2021/03/17 21:00:00",
        "live_event_end_time": "2021/03/17 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292780-card-thumbnail-1614101333.png",
        "published_on": "2021/03/17 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292806,
        "type": "coach-stream",
        "title": "Linear Chop Building",
        "live_event_start_time": "2021/03/18 17:00:00",
        "live_event_end_time": "2021/03/18 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292806-card-thumbnail-1614102455.png",
        "published_on": "2021/03/18 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Sarah Thawer"
        ],
        "isLive": false
    },
    {
        "id": 292815,
        "type": "coach-stream",
        "title": "Here Boy, Roll Over!",
        "live_event_start_time": "2021/03/18 20:00:00",
        "live_event_end_time": "2021/03/18 21:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292815-card-thumbnail-1614103501.png",
        "published_on": "2021/03/18 20:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "John Wooton"
        ],
        "isLive": false
    },
    {
        "id": 292794,
        "type": "coach-stream",
        "title": "I Taught This WRONG In Past Lessons",
        "live_event_start_time": "2021/03/20 18:00:00",
        "live_event_end_time": "2021/03/20 19:15:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292794-card-thumbnail-1614100566.png",
        "published_on": "2021/03/20 18:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Jared Falk"
        ],
        "isLive": false
    },
    {
        "id": 292832,
        "type": "coach-stream",
        "title": "Q&A & Practice With Kaz",
        "live_event_start_time": "2021/03/21 17:00:00",
        "live_event_end_time": "2021/03/21 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292832-card-thumbnail-1614104979.png",
        "published_on": "2021/03/21 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292724,
        "type": "coach-stream",
        "title": "Contracts & Visas",
        "live_event_start_time": "2021/03/22 17:00:00",
        "live_event_end_time": "2021/03/22 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292724-card-thumbnail-1614106345.png",
        "published_on": "2021/03/22 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292736,
        "type": "coach-stream",
        "title": "Ask Me Anything (General Q&A)",
        "live_event_start_time": "2021/03/23 16:00:00",
        "live_event_end_time": "2021/03/23 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292736-card-thumbnail-1614106485.png",
        "published_on": "2021/03/23 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 293041,
        "type": "coach-stream",
        "title": "Let's Talk!",
        "live_event_start_time": "2021/03/23 19:30:00",
        "live_event_end_time": "2021/03/23 20:30:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/293041-card-thumbnail-1614283512.png",
        "published_on": "2021/03/23 19:30:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Larnell Lewis"
        ],
        "isLive": false
    },
    {
        "id": 292753,
        "type": "coach-stream",
        "title": "Your Q&A With Domino!",
        "live_event_start_time": "2021/03/23 22:00:00",
        "live_event_end_time": "2021/03/23 23:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292753-card-thumbnail-1614106759.png",
        "published_on": "2021/03/23 22:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Domino Santantonio"
        ],
        "isLive": false
    },
    {
        "id": 292764,
        "type": "coach-stream",
        "title": "Play Beats For Speed",
        "live_event_start_time": "2021/03/24 19:00:00",
        "live_event_end_time": "2021/03/24 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292764-card-thumbnail-1614107030.png",
        "published_on": "2021/03/24 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292781,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #11",
        "live_event_start_time": "2021/03/24 21:00:00",
        "live_event_end_time": "2021/03/24 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292781-card-thumbnail-1614101357.png",
        "published_on": "2021/03/24 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292807,
        "type": "coach-stream",
        "title": "Let's Work On That \"Weaker\"  Hand!",
        "live_event_start_time": "2021/03/25 17:00:00",
        "live_event_end_time": "2021/03/25 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292807-card-thumbnail-1614102488.png",
        "published_on": "2021/03/25 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Sarah Thawer"
        ],
        "isLive": false
    },
    {
        "id": 292816,
        "type": "coach-stream",
        "title": "Tres Campanetos",
        "live_event_start_time": "2021/03/25 20:00:00",
        "live_event_end_time": "2021/03/25 21:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292816-card-thumbnail-1614103531.png",
        "published_on": "2021/03/25 20:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "John Wooton"
        ],
        "isLive": false
    },
    {
        "id": 281199,
        "type": "coach-stream",
        "title": "Drum Tips From A Drum Tech",
        "live_event_start_time": "2021/03/27 18:00:00",
        "live_event_end_time": "2021/03/27 19:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281199-card-thumbnail-1614100026.png",
        "published_on": "2021/03/27 18:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Jared Falk"
        ],
        "isLive": false
    },
    {
        "id": 292825,
        "type": "coach-stream",
        "title": "Write A Song With Kaz",
        "live_event_start_time": "2021/03/28 17:00:00",
        "live_event_end_time": "2021/03/28 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292825-card-thumbnail-1614105019.png",
        "published_on": "2021/03/28 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292725,
        "type": "coach-stream",
        "title": "Digital & Phyiscal Drummer",
        "live_event_start_time": "2021/03/29 17:00:00",
        "live_event_end_time": "2021/03/29 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292725-card-thumbnail-1614106356.png",
        "published_on": "2021/03/29 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292737,
        "type": "coach-stream",
        "title": "Expanding Paradiddles Even Further",
        "live_event_start_time": "2021/03/30 16:00:00",
        "live_event_end_time": "2021/03/30 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292737-card-thumbnail-1614106610.png",
        "published_on": "2021/03/30 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 293042,
        "type": "coach-stream",
        "title": "Trading?! What's That?",
        "live_event_start_time": "2021/03/30 19:30:00",
        "live_event_end_time": "2021/03/30 20:30:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/293042-card-thumbnail-1614283503.png",
        "published_on": "2021/03/30 19:30:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Larnell Lewis"
        ],
        "isLive": false
    },
    {
        "id": 292754,
        "type": "coach-stream",
        "title": "How To Make It As A Drummer On YouTube!",
        "live_event_start_time": "2021/03/30 22:00:00",
        "live_event_end_time": "2021/03/30 23:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292754-card-thumbnail-1614106771.png",
        "published_on": "2021/03/30 22:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Domino Santantonio"
        ],
        "isLive": false
    },
    {
        "id": 292765,
        "type": "coach-stream",
        "title": "Rosanna On Steroids",
        "live_event_start_time": "2021/03/31 19:00:00",
        "live_event_end_time": "2021/03/31 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292765-card-thumbnail-1614107042.png",
        "published_on": "2021/03/31 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292782,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #12",
        "live_event_start_time": "2021/03/31 21:00:00",
        "live_event_end_time": "2021/03/31 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292782-card-thumbnail-1614101378.png",
        "published_on": "2021/03/31 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292808,
        "type": "coach-stream",
        "title": "Soloing Over Ostinatos & Developing Independence",
        "live_event_start_time": "2021/04/01 17:00:00",
        "live_event_end_time": "2021/04/01 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292808-card-thumbnail-1614102519.png",
        "published_on": "2021/04/01 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Sarah Thawer"
        ],
        "isLive": false
    },
    {
        "id": 292817,
        "type": "coach-stream",
        "title": "I Love A Good Book!",
        "live_event_start_time": "2021/04/01 20:00:00",
        "live_event_end_time": "2021/04/01 21:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292817-card-thumbnail-1614103558.png",
        "published_on": "2021/04/01 20:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "John Wooton"
        ],
        "isLive": false
    },
    {
        "id": 292796,
        "type": "coach-stream",
        "title": "Heel Toe Bass Drum Techniques",
        "live_event_start_time": "2021/04/03 18:00:00",
        "live_event_end_time": "2021/04/03 19:15:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292796-card-thumbnail-1614100681.png",
        "published_on": "2021/04/03 18:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Jared Falk"
        ],
        "isLive": false
    },
    {
        "id": 292829,
        "type": "coach-stream",
        "title": "Perform The Song With Kaz",
        "live_event_start_time": "2021/04/04 17:00:00",
        "live_event_end_time": "2021/04/04 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292829-card-thumbnail-1614105049.png",
        "published_on": "2021/04/04 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292726,
        "type": "coach-stream",
        "title": "What Musical Directors Look For",
        "live_event_start_time": "2021/04/05 17:00:00",
        "live_event_end_time": "2021/04/05 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292726-card-thumbnail-1614106368.png",
        "published_on": "2021/04/05 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292738,
        "type": "coach-stream",
        "title": "Developing Stick Control - Moving Accents",
        "live_event_start_time": "2021/04/06 16:00:00",
        "live_event_end_time": "2021/04/06 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292738-card-thumbnail-1614106621.png",
        "published_on": "2021/04/06 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 293043,
        "type": "coach-stream",
        "title": "Active Listening",
        "live_event_start_time": "2021/04/06 19:30:00",
        "live_event_end_time": "2021/04/06 20:30:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/293043-card-thumbnail-1614283494.png",
        "published_on": "2021/04/06 19:30:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Larnell Lewis"
        ],
        "isLive": false
    },
    {
        "id": 292755,
        "type": "coach-stream",
        "title": "How To Incorporate The Hi-Hat In Your Playing!",
        "live_event_start_time": "2021/04/06 22:00:00",
        "live_event_end_time": "2021/04/06 23:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292755-card-thumbnail-1614106781.png",
        "published_on": "2021/04/06 22:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Domino Santantonio"
        ],
        "isLive": false
    },
    {
        "id": 292766,
        "type": "coach-stream",
        "title": "Your Q&A With Shcack",
        "live_event_start_time": "2021/04/07 19:00:00",
        "live_event_end_time": "2021/04/07 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292766-card-thumbnail-1614107053.png",
        "published_on": "2021/04/07 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292783,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #13",
        "live_event_start_time": "2021/04/07 21:00:00",
        "live_event_end_time": "2021/04/07 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292783-card-thumbnail-1614101398.png",
        "published_on": "2021/04/07 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292809,
        "type": "coach-stream",
        "title": "3 Way Coordination",
        "live_event_start_time": "2021/04/08 17:00:00",
        "live_event_end_time": "2021/04/08 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292809-card-thumbnail-1614102552.png",
        "published_on": "2021/04/08 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Sarah Thawer"
        ],
        "isLive": false
    },
    {
        "id": 292818,
        "type": "coach-stream",
        "title": "Rudimental Solo #1",
        "live_event_start_time": "2021/04/08 20:00:00",
        "live_event_end_time": "2021/04/08 21:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292818-card-thumbnail-1614103613.png",
        "published_on": "2021/04/08 20:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "John Wooton"
        ],
        "isLive": false
    },
    {
        "id": 292797,
        "type": "coach-stream",
        "title": "Single Paradiddle Madness",
        "live_event_start_time": "2021/04/10 18:00:00",
        "live_event_end_time": "2021/04/10 19:15:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292797-card-thumbnail-1614100819.png",
        "published_on": "2021/04/10 18:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Jared Falk"
        ],
        "isLive": false
    },
    {
        "id": 292833,
        "type": "coach-stream",
        "title": "Q&A & Practice With Kaz",
        "live_event_start_time": "2021/04/11 17:00:00",
        "live_event_end_time": "2021/04/11 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292833-card-thumbnail-1614105086.png",
        "published_on": "2021/04/11 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292727,
        "type": "coach-stream",
        "title": "Watching Other Drummers",
        "live_event_start_time": "2021/04/12 17:00:00",
        "live_event_end_time": "2021/04/12 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292727-card-thumbnail-1614106379.png",
        "published_on": "2021/04/12 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292739,
        "type": "coach-stream",
        "title": "Easy Fills",
        "live_event_start_time": "2021/04/13 16:00:00",
        "live_event_end_time": "2021/04/13 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292739-card-thumbnail-1614106631.png",
        "published_on": "2021/04/13 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 293045,
        "type": "coach-stream",
        "title": "Learning Songs",
        "live_event_start_time": "2021/04/13 19:30:00",
        "live_event_end_time": "2021/04/13 20:30:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/293045-card-thumbnail-1614283486.png",
        "published_on": "2021/04/13 19:30:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Larnell Lewis"
        ],
        "isLive": false
    },
    {
        "id": 292756,
        "type": "coach-stream",
        "title": "Learn My Version Of “My Ex’s Best Friend” By Machine Gun Kelly",
        "live_event_start_time": "2021/04/13 22:00:00",
        "live_event_end_time": "2021/04/13 23:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292756-card-thumbnail-1614106793.png",
        "published_on": "2021/04/13 22:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Domino Santantonio"
        ],
        "isLive": false
    },
    {
        "id": 292767,
        "type": "coach-stream",
        "title": "Who's Afraid Of 138 BPM?",
        "live_event_start_time": "2021/04/14 19:00:00",
        "live_event_end_time": "2021/04/14 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292767-card-thumbnail-1614107065.png",
        "published_on": "2021/04/14 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292784,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #14",
        "live_event_start_time": "2021/04/14 21:00:00",
        "live_event_end_time": "2021/04/14 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292784-card-thumbnail-1614101451.png",
        "published_on": "2021/04/14 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292810,
        "type": "coach-stream",
        "title": "4 Way Coordination",
        "live_event_start_time": "2021/04/15 17:00:00",
        "live_event_end_time": "2021/04/15 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292810-card-thumbnail-1614102582.png",
        "published_on": "2021/04/15 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Sarah Thawer"
        ],
        "isLive": false
    },
    {
        "id": 292819,
        "type": "coach-stream",
        "title": "Don’t Underestimate Me!",
        "live_event_start_time": "2021/04/15 20:00:00",
        "live_event_end_time": "2021/04/15 21:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292819-card-thumbnail-1614103654.png",
        "published_on": "2021/04/15 20:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "John Wooton"
        ],
        "isLive": false
    },
    {
        "id": 292799,
        "type": "coach-stream",
        "title": "How To Not Sound Like a Beginner Drummer",
        "live_event_start_time": "2021/04/17 18:00:00",
        "live_event_end_time": "2021/04/17 19:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292799-card-thumbnail-1614100893.png",
        "published_on": "2021/04/17 18:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Jared Falk"
        ],
        "isLive": false
    },
    {
        "id": 292826,
        "type": "coach-stream",
        "title": "Write A Song With Kaz",
        "live_event_start_time": "2021/04/18 17:00:00",
        "live_event_end_time": "2021/04/18 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292826-card-thumbnail-1614105124.png",
        "published_on": "2021/04/18 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292728,
        "type": "coach-stream",
        "title": "Creating A Drum Cover",
        "live_event_start_time": "2021/04/19 17:00:00",
        "live_event_end_time": "2021/04/19 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292728-card-thumbnail-1614106389.png",
        "published_on": "2021/04/19 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292740,
        "type": "coach-stream",
        "title": "Ask Me Anything (General Q&A)",
        "live_event_start_time": "2021/04/20 16:00:00",
        "live_event_end_time": "2021/04/20 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292740-card-thumbnail-1614106642.png",
        "published_on": "2021/04/20 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 293046,
        "type": "coach-stream",
        "title": "Drum Workout Session: Single Kick",
        "live_event_start_time": "2021/04/20 19:30:00",
        "live_event_end_time": "2021/04/20 20:30:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/293046-card-thumbnail-1614283477.png",
        "published_on": "2021/04/20 19:30:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Larnell Lewis"
        ],
        "isLive": false
    },
    {
        "id": 292757,
        "type": "coach-stream",
        "title": "Your Q&A With Domino!",
        "live_event_start_time": "2021/04/20 22:00:00",
        "live_event_end_time": "2021/04/20 23:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292757-card-thumbnail-1614106804.png",
        "published_on": "2021/04/20 22:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Domino Santantonio"
        ],
        "isLive": false
    },
    {
        "id": 292768,
        "type": "coach-stream",
        "title": "Don't Let The Blues Fool You",
        "live_event_start_time": "2021/04/21 19:00:00",
        "live_event_end_time": "2021/04/21 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292768-card-thumbnail-1614107078.png",
        "published_on": "2021/04/21 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292785,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #15",
        "live_event_start_time": "2021/04/21 21:00:00",
        "live_event_end_time": "2021/04/21 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292785-card-thumbnail-1614101475.png",
        "published_on": "2021/04/21 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292811,
        "type": "coach-stream",
        "title": "QUESTION TIME! Ask Me Anything!",
        "live_event_start_time": "2021/04/22 17:00:00",
        "live_event_end_time": "2021/04/22 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292811-card-thumbnail-1614102616.png",
        "published_on": "2021/04/22 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Sarah Thawer"
        ],
        "isLive": false
    },
    {
        "id": 292820,
        "type": "coach-stream",
        "title": "Plain Ole Cheese",
        "live_event_start_time": "2021/04/22 20:00:00",
        "live_event_end_time": "2021/04/22 21:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292820-card-thumbnail-1614103692.png",
        "published_on": "2021/04/22 20:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "John Wooton"
        ],
        "isLive": false
    },
    {
        "id": 292801,
        "type": "coach-stream",
        "title": "10 Ways To Make Money Playing Drums",
        "live_event_start_time": "2021/04/24 18:00:00",
        "live_event_end_time": "2021/04/24 19:15:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292801-card-thumbnail-1614101115.png",
        "published_on": "2021/04/24 18:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Jared Falk"
        ],
        "isLive": false
    },
    {
        "id": 292830,
        "type": "coach-stream",
        "title": "Perform The Song With Kaz",
        "live_event_start_time": "2021/04/25 17:00:00",
        "live_event_end_time": "2021/04/25 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292830-card-thumbnail-1614105172.png",
        "published_on": "2021/04/25 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292729,
        "type": "coach-stream",
        "title": "Push Pull",
        "live_event_start_time": "2021/04/26 17:00:00",
        "live_event_end_time": "2021/04/26 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292729-card-thumbnail-1614106398.png",
        "published_on": "2021/04/26 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292741,
        "type": "coach-stream",
        "title": "Triplets And How To Use Them",
        "live_event_start_time": "2021/04/27 16:00:00",
        "live_event_end_time": "2021/04/27 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292741-card-thumbnail-1614106652.png",
        "published_on": "2021/04/27 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 293047,
        "type": "coach-stream",
        "title": "Trade Secrets",
        "live_event_start_time": "2021/04/27 19:30:00",
        "live_event_end_time": "2021/04/27 20:30:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/293047-card-thumbnail-1614283468.png",
        "published_on": "2021/04/27 19:30:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Larnell Lewis"
        ],
        "isLive": false
    },
    {
        "id": 292758,
        "type": "coach-stream",
        "title": "How To Be Prepared For Live Shows!",
        "live_event_start_time": "2021/04/27 22:00:00",
        "live_event_end_time": "2021/04/27 23:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292758-card-thumbnail-1614106815.png",
        "published_on": "2021/04/27 22:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Domino Santantonio"
        ],
        "isLive": false
    },
    {
        "id": 292769,
        "type": "coach-stream",
        "title": "Fix Your Tempo!",
        "live_event_start_time": "2021/04/28 19:00:00",
        "live_event_end_time": "2021/04/28 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292769-card-thumbnail-1614107089.png",
        "published_on": "2021/04/28 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292786,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #16",
        "live_event_start_time": "2021/04/28 21:00:00",
        "live_event_end_time": "2021/04/28 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292786-card-thumbnail-1614101498.png",
        "published_on": "2021/04/28 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292834,
        "type": "coach-stream",
        "title": "Q&A & Practice With Kaz",
        "live_event_start_time": "2021/05/02 17:00:00",
        "live_event_end_time": "2021/05/02 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292834-card-thumbnail-1614105214.png",
        "published_on": "2021/05/02 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292730,
        "type": "coach-stream",
        "title": "Road Map Beginner - Creating Your Journey",
        "live_event_start_time": "2021/05/03 17:00:00",
        "live_event_end_time": "2021/05/03 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292730-card-thumbnail-1614106410.png",
        "published_on": "2021/05/03 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292742,
        "type": "coach-stream",
        "title": "Hand Drumming 101",
        "live_event_start_time": "2021/05/04 16:00:00",
        "live_event_end_time": "2021/05/04 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292742-card-thumbnail-1614106662.png",
        "published_on": "2021/05/04 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 292770,
        "type": "coach-stream",
        "title": "Questions? Aim Well & Shoot!",
        "live_event_start_time": "2021/05/05 19:00:00",
        "live_event_end_time": "2021/05/05 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292770-card-thumbnail-1614107099.png",
        "published_on": "2021/05/05 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292787,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #17",
        "live_event_start_time": "2021/05/05 21:00:00",
        "live_event_end_time": "2021/05/05 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292787-card-thumbnail-1614101520.png",
        "published_on": "2021/05/05 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292827,
        "type": "coach-stream",
        "title": "Write A Song With Kaz",
        "live_event_start_time": "2021/05/09 17:00:00",
        "live_event_end_time": "2021/05/09 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292827-card-thumbnail-1614105277.png",
        "published_on": "2021/05/09 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292731,
        "type": "coach-stream",
        "title": "Making Money On Youtube",
        "live_event_start_time": "2021/05/10 17:00:00",
        "live_event_end_time": "2021/05/10 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292731-card-thumbnail-1614106424.png",
        "published_on": "2021/05/10 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Matt McGuire"
        ],
        "isLive": false
    },
    {
        "id": 292743,
        "type": "coach-stream",
        "title": "Developing Stick Control 2 - Adding Independence",
        "live_event_start_time": "2021/05/11 16:00:00",
        "live_event_end_time": "2021/05/11 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292743-card-thumbnail-1614106671.png",
        "published_on": "2021/05/11 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 292771,
        "type": "coach-stream",
        "title": "Good Sound, Better Practice",
        "live_event_start_time": "2021/05/12 19:00:00",
        "live_event_end_time": "2021/05/12 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292771-card-thumbnail-1614107113.png",
        "published_on": "2021/05/12 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292788,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #18",
        "live_event_start_time": "2021/05/12 21:00:00",
        "live_event_end_time": "2021/05/12 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292788-card-thumbnail-1614101537.png",
        "published_on": "2021/05/12 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292831,
        "type": "coach-stream",
        "title": "Perform The Song With Kaz",
        "live_event_start_time": "2021/05/16 17:00:00",
        "live_event_end_time": "2021/05/16 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292831-card-thumbnail-1614105306.png",
        "published_on": "2021/05/16 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292744,
        "type": "coach-stream",
        "title": "Ask Me Anything (General Q&A)",
        "live_event_start_time": "2021/05/18 16:00:00",
        "live_event_end_time": "2021/05/18 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292744-card-thumbnail-1614106683.png",
        "published_on": "2021/05/18 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 292772,
        "type": "coach-stream",
        "title": "Vivaldi? Yes, Vivaldi!",
        "live_event_start_time": "2021/05/19 19:00:00",
        "live_event_end_time": "2021/05/19 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292772-card-thumbnail-1614107123.png",
        "published_on": "2021/05/19 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292789,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #19",
        "live_event_start_time": "2021/05/19 21:00:00",
        "live_event_end_time": "2021/05/19 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292789-card-thumbnail-1614101556.png",
        "published_on": "2021/05/19 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292835,
        "type": "coach-stream",
        "title": "Q&A & Practice With Kaz",
        "live_event_start_time": "2021/05/23 17:00:00",
        "live_event_end_time": "2021/05/23 18:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292835-card-thumbnail-1614105339.png",
        "published_on": "2021/05/23 17:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Kaz Rodriguez"
        ],
        "isLive": false
    },
    {
        "id": 292745,
        "type": "coach-stream",
        "title": "Developing Brushwork",
        "live_event_start_time": "2021/05/25 16:00:00",
        "live_event_end_time": "2021/05/25 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292745-card-thumbnail-1614106694.png",
        "published_on": "2021/05/25 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 292773,
        "type": "coach-stream",
        "title": "Cleaning Your Cymbals Doesn't Clean Up Your Drumming.",
        "live_event_start_time": "2021/05/26 19:00:00",
        "live_event_end_time": "2021/05/26 20:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292773-card-thumbnail-1614107133.png",
        "published_on": "2021/05/26 19:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Michael Schack"
        ],
        "isLive": false
    },
    {
        "id": 292790,
        "type": "coach-stream",
        "title": "The Todd Sucherman Show #20",
        "live_event_start_time": "2021/05/26 21:00:00",
        "live_event_end_time": "2021/05/26 22:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292790-card-thumbnail-1614101577.png",
        "published_on": "2021/05/26 21:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Todd Sucherman"
        ],
        "isLive": false
    },
    {
        "id": 292746,
        "type": "coach-stream",
        "title": "Flams As Fills",
        "live_event_start_time": "2021/06/01 16:00:00",
        "live_event_end_time": "2021/06/01 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292746-card-thumbnail-1614106705.png",
        "published_on": "2021/06/01 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 292747,
        "type": "coach-stream",
        "title": "How To Shuffle Like A Boss!",
        "live_event_start_time": "2021/06/08 16:00:00",
        "live_event_end_time": "2021/06/08 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292747-card-thumbnail-1614106715.png",
        "published_on": "2021/06/08 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    },
    {
        "id": 292748,
        "type": "coach-stream",
        "title": "Ask Me Anything (General Q&A)",
        "live_event_start_time": "2021/06/15 16:00:00",
        "live_event_end_time": "2021/06/15 17:00:00",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292748-card-thumbnail-1614106725.png",
        "published_on": "2021/06/15 16:00:00",
        "is_added_to_primary_playlist": false,
        "length_in_seconds": null,
        "parent_id": null,
        "completed": false,
        "started": false,
        "instructors": [
            "Dorothea Taylor"
        ],
        "isLive": false
    }
]
```
