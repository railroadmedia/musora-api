## Pack lesson
Return pack lesson data.
The results respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-1e7e42ed-c249-4a87-aef8-6fa0aa46cd9a"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/pack/lesson/{id}`

### Permissions
    - Only authenticated user that own pack can access the endpoint

### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/pack/lesson/234769',
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
    "id": 234769,
    "type": "semester-pack-lesson",
    "title": "Set Up Your Practice Space",
    "description": "Having a comfortable, personalized practice space is vital to ensuring efficient and quality practice time. In this video, I’ll cover topics such as throne and snare height, pedal placement, hi hat spacing, tom angles, and cymbal orientation. It can take a long time to establish the most optimal set up for you, so don’t rush this process! I’ll also go over other tools you should have in order to get the best value out of this course. You’ll be spending a lot of time here, so take the time to get cozy so you don’t have any excuses not to practice. Have fun with it!",
    "completed": false,
    "started": true,
    "progress_percent": 0,
    "related_lessons": [
        {
            "id": 234768,
            "type": "semester-pack-lesson",
            "title": "Practice Planner",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/pack-dvds/550/ime-02.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:41:06",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234768",
            "length_in_seconds": "217",
            "parent_id": 234577
        },
        {
            "id": 234767,
            "type": "semester-pack-lesson",
            "title": "Practice Pad Warm-Ups",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/pack-dvds/550/ime-03.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:42:06",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234767",
            "length_in_seconds": "441",
            "parent_id": 234577
        },
        {
            "id": 234761,
            "type": "semester-pack-lesson",
            "title": "Right, Left, Bass",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208668_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:43:03",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234761",
            "length_in_seconds": "1140",
            "parent_id": 234577
        },
        {
            "id": 234755,
            "type": "semester-pack-lesson",
            "title": "Right, Bass, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208669_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:44:01",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234755",
            "length_in_seconds": "788",
            "parent_id": 234577
        },
        {
            "id": 234749,
            "type": "semester-pack-lesson",
            "title": "Bass, Right, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208670_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:44:58",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": true,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234749",
            "length_in_seconds": "1077",
            "parent_id": 234577
        },
        {
            "id": 234743,
            "type": "semester-pack-lesson",
            "title": "Bass, Bass, Right",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208671_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:45:55",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234743",
            "length_in_seconds": "965",
            "parent_id": 234577
        },
        {
            "id": 234737,
            "type": "semester-pack-lesson",
            "title": "Right, Bass, Bass",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208672_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:46:50",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234737",
            "length_in_seconds": "899",
            "parent_id": 234577
        },
        {
            "id": 234731,
            "type": "semester-pack-lesson",
            "title": "Bass, Right, Bass",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208673_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:47:45",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": true,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234731",
            "length_in_seconds": "844",
            "parent_id": 234577
        },
        {
            "id": 234725,
            "type": "semester-pack-lesson",
            "title": "Right, Bass, Left, Bass",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208674_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:48:41",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234725",
            "length_in_seconds": "980",
            "parent_id": 234577
        },
        {
            "id": 234719,
            "type": "semester-pack-lesson",
            "title": "Bass, Right, Bass, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208675_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:49:37",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234719",
            "length_in_seconds": "1065",
            "parent_id": 234577
        },
        {
            "id": 234713,
            "type": "semester-pack-lesson",
            "title": "Bass, Bass, Right, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208676_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:50:34",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": true,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234713",
            "length_in_seconds": "1014",
            "parent_id": 234577
        },
        {
            "id": 234707,
            "type": "semester-pack-lesson",
            "title": "Right, Left, Bass, Bass",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208677_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:51:31",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234707",
            "length_in_seconds": "915",
            "parent_id": 234577
        },
        {
            "id": 234701,
            "type": "semester-pack-lesson",
            "title": "Right, Bass, Bass, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208678_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:52:28",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234701",
            "length_in_seconds": "898",
            "parent_id": 234577
        },
        {
            "id": 234694,
            "type": "semester-pack-lesson",
            "title": "Triplets - Left, Left, Bass",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208679_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:53:25",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234694",
            "length_in_seconds": "1061",
            "parent_id": 234577
        },
        {
            "id": 234688,
            "type": "semester-pack-lesson",
            "title": "Triplets - Left, Bass, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208680_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:54:22",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234688",
            "length_in_seconds": "894",
            "parent_id": 234577
        },
        {
            "id": 234682,
            "type": "semester-pack-lesson",
            "title": "Triplets - Bass, Left, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208681_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:55:20",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234682",
            "length_in_seconds": "974",
            "parent_id": 234577
        },
        {
            "id": 234676,
            "type": "semester-pack-lesson",
            "title": "Triplets - Left, Bass, Bass",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208682_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:56:17",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234676",
            "length_in_seconds": "903",
            "parent_id": 234577
        },
        {
            "id": 234670,
            "type": "semester-pack-lesson",
            "title": "Triplets - Bass, Bass, Left",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208683_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:57:14",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234670",
            "length_in_seconds": "820",
            "parent_id": 234577
        },
        {
            "id": 234661,
            "type": "semester-pack-lesson",
            "title": "Right Foot Left Foot",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208684_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:58:10",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234661",
            "length_in_seconds": "1408",
            "parent_id": 234577
        },
        {
            "id": 234652,
            "type": "semester-pack-lesson",
            "title": "Bossa Nova Foot Ostinato",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208685_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 20:59:06",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234652",
            "length_in_seconds": "1530",
            "parent_id": 234577
        },
        {
            "id": 234643,
            "type": "semester-pack-lesson",
            "title": "Tumbao Foot Ostinato",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208686_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:00:02",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234643",
            "length_in_seconds": "1602",
            "parent_id": 234577
        },
        {
            "id": 234634,
            "type": "semester-pack-lesson",
            "title": "Kick, Hi-Hat, Hi-Hat",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208687_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:00:58",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234634",
            "length_in_seconds": "1594",
            "parent_id": 234577
        },
        {
            "id": 234624,
            "type": "semester-pack-lesson",
            "title": "Triplets Over Your Ostinato",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208688_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:01:53",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234624",
            "length_in_seconds": "1084",
            "parent_id": 234577
        },
        {
            "id": 234614,
            "type": "semester-pack-lesson",
            "title": "Double Stroke Roll With Your Feet",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208689_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:02:49",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234614",
            "length_in_seconds": "1219",
            "parent_id": 234577
        },
        {
            "id": 234605,
            "type": "semester-pack-lesson",
            "title": "Weak Hand Independence & Control",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208690_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:03:46",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234605",
            "length_in_seconds": "1235",
            "parent_id": 234577
        },
        {
            "id": 234596,
            "type": "semester-pack-lesson",
            "title": "Lead Hand Independence",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208691_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:04:42",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234596",
            "length_in_seconds": "1227",
            "parent_id": 234577
        },
        {
            "id": 234587,
            "type": "semester-pack-lesson",
            "title": "The Bass Drum Foot",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208692_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:05:38",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234587",
            "length_in_seconds": "1194",
            "parent_id": 234577
        },
        {
            "id": 234578,
            "type": "semester-pack-lesson",
            "title": "The Bass Drum Foot Continued",
            "artist": null,
            "style": null,
            "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208693_thumbnail_360p.jpg",
            "status": "published",
            "published_on": "2019/10/10 21:06:35",
            "completed": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234578",
            "length_in_seconds": "1579",
            "parent_id": 234577
        }
    ],
    "next_lesson": {
        "title": "Practice Planner",
        "artist": null,
        "style": null,
        "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/pack-dvds/550/ime-02.jpg",
        "id": 234768,
        "type": "semester-pack-lesson",
        "status": "published",
        "published_on": "2019/10/10 20:41:06",
        "completed": false,
        "progress_percent": 0,
        "is_added_to_primary_playlist": false,
        "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234768",
        "length_in_seconds": "217",
        "parent_id": 234577
    },
    "previous_lesson": null,
    "parent": {
        "current_lesson": {
            "id": 234768,
            "type": "semester-pack-lesson",
            "title": "Practice Planner",
            "description": "Now that your practice space is fully prepared for efficient practice, in this video we’ll be discussing creating a practice routine. As developing musicians we’re constantly told we should be organized and have a structured practice schedule, but what does that look like for you? Included with this lesson is a PDF download that will help you keep track of your practice time and also let you know when each lesson in this course is released. I recommend scheduling 30 minutes each day to practice, but the longer the better! Put this page in your practice space, or even better, a place where you’ll be constantly reminded so you can stay on track and hold yourself accountable.",
            "completed": false,
            "started": true,
            "progress_percent": 0,
            "vimeo_video_id": "195506120",
            "youtube_video_id": null,
            "length_in_seconds": "217",
            "xp": 150,
            "total_xp": "150",
            "xp_bonus": 150,
            "published_on": "2019/10/10 20:41:06",
            "is_added_to_primary_playlist": false
        }
    },
    "next_content_type": "lesson",
    "total_comments": 16,
    "comments": [
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 345636,
            "id": 166145,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": "0",
                "display_name": "stephensolaikadu41604",
                "xp_level": "Enthusiast I"
            },
            "comment": "Whoa this pack is sooo good!!!!!",
            "created_on": "2021-01-25 16:06:39"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 410066,
            "id": 164770,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/410066_1609361338079-1609361345-410066.jpg",
                "xp": 0,
                "display_name": "RevPaul",
                "xp_level": "Enthusiast I"
            },
            "comment": "This is helpful, even for this 40 year player. I am starting fresh and want to lose bad habits and lazy habits. I haven't rethought my kit set up in a long time and now I'm actually playing with heights and arrangements again!",
            "created_on": "2021-01-19 23:19:45"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 391456,
            "id": 145666,
            "replies": [
                {
                    "id": 145667,
                    "content_id": 234769,
                    "comment": "I could never mark this lesson as completed.....",
                    "parent_id": 145666,
                    "user_id": 391456,
                    "conversation_status": "open",
                    "display_name": "",
                    "created_on": "2020-09-26 00:33:05",
                    "deleted_at": null,
                    "like_count": 0,
                    "like_users": [],
                    "is_liked": false,
                    "user": {
                        "display_name": "udi akuka",
                        "xp": 0,
                        "xp_level": "Enthusiast I",
                        "access_level": "edge",
                        "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/391456_1598567968724.jpg",
                        "level_number": "1.0",
                        "isAdmin": false
                    }
                }
            ],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/391456_1598567968724.jpg",
                "xp": 0,
                "display_name": "udi akuka",
                "xp_level": "Enthusiast I"
            },
            "comment": "I just got my first kit a few days ago, and man... it feels like i would never finish to set up my space.... each time i think i got the perfect positioning for my kit, and than i feel like i need to slightly change the height of the ride, but wait! now its blocking my low tom, and when i fix this, the rack toms are not level, but i feel like i need to change the height of my chair again now, so i do this, and now the snare isnt at acomfortable height, and now i need to change the possision of the Hi Hat... you see where im going with this....",
            "created_on": "2020-09-26 00:32:22"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 360437,
            "id": 136955,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": 0,
                "display_name": "emilrocks20026",
                "xp_level": "Enthusiast I"
            },
            "comment": "i've been looking for this course and trying to find a way to be able to sit down and practise without the simple distractions; GREAT!",
            "created_on": "2020-07-23 05:58:05"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 321337,
            "id": 134388,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": 0,
                "display_name": "Cheryl Renee",
                "xp_level": "Enthusiast I"
            },
            "comment": "Excellent! I'm going to look at and readjust my kits if needed:  I have both an acoustic and an electronic kit.  I'm going to work on both for this course.  Unfortunately, I had to stop playng for awhile due to a knee injury.  I do not want to reinjure or aggravate the knee, so I will defintely make sure I have a good ergonomic set-up.  Thanks, Very informative!!",
            "created_on": "2020-07-06 22:12:23"
        },
        {
            "is_liked": false,
            "like_count": 3,
            "user_id": 367284,
            "id": 134045,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/367284_1593880705971.jpg",
                "xp": 0,
                "display_name": "187industries",
                "xp_level": "Enthusiast I"
            },
            "comment": "Yo, I'm not trying to sound negative, but I already have this stuff set up and ready. Might I make a suggestion and include a mirror as a practice tool because it does help see what leading with the opposite hand is like before following threw.",
            "created_on": "2020-07-04 17:58:39"
        },
        {
            "is_liked": false,
            "like_count": 2,
            "user_id": 354781,
            "id": 129123,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": 0,
                "display_name": "keli.muli.km20292",
                "xp_level": "Enthusiast I"
            },
            "comment": "\"Independence made easy \". Am not a digital ( computer schooled person ) but love this idea . Should  I accept, will it expire by date ?",
            "created_on": "2020-06-06 19:32:05"
        },
        {
            "is_liked": false,
            "like_count": 3,
            "user_id": 156246,
            "id": 117080,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/156246_1600376269478.jpg",
                "xp": 0,
                "display_name": "Kristeta",
                "xp_level": "Enthusiast I"
            },
            "comment": "This lesson answered some of my questions. I really love how Jared talked about the reasons and purpose for why each drum is set up as such. Very Helpful. Thank you very much. That inspired me today! I am now looking at a space in the house to really use my a-kit and have prepared my rubber pads too to put onto each tom! Hope everyone is well and staying safe. Take care :) Am all fired up starting on this course!!!! ",
            "created_on": "2020-03-31 23:10:49"
        },
        {
            "is_liked": false,
            "like_count": 2,
            "user_id": 156246,
            "id": 117079,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/156246_1600376269478.jpg",
                "xp": 0,
                "display_name": "Kristeta",
                "xp_level": "Enthusiast I"
            },
            "comment": "This lesson answered some of my questions. I really love how Jared talked about the reasons and purpose for why each drum is set up as such. Very Helpful. Thank you very much. That inspired me today! I am now looking at a space in the house to really use my a-kit and have prepared my rubber pads too to put onto each tom! Hope everyone is well and staying safe. Take care :) Am all fired up starting on this course!!!! ",
            "created_on": "2020-03-31 23:10:29"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 340982,
            "id": 103288,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": 0,
                "display_name": "sorenberlev643591",
                "xp_level": "Enthusiast I"
            },
            "comment": "i just youse my own clik so i don't youse the one there is in the corse i need a some other in ear or so i just youse normel head pfones so i can put them tru my mixer to get good volume is there a good brand and where to bay i live in denmark copenhagen so ",
            "created_on": "2019-11-20 22:16:46"
        }
    ],
    "video_playback_endpoints": [
        {
            "file": "https://player.vimeo.com/external/195521686.sd.mp4?s=3c19c8ea68e19600b5d9543130f22368b6cfaad2&profile_id=164&oauth2_token_id=1284792283",
            "width": 640,
            "height": 360
        },
        {
            "file": "https://player.vimeo.com/external/195521686.sd.mp4?s=3c19c8ea68e19600b5d9543130f22368b6cfaad2&profile_id=165&oauth2_token_id=1284792283",
            "width": 960,
            "height": 540
        },
        {
            "file": "https://player.vimeo.com/external/195521686.hd.mp4?s=133f40368eca3f16d5ff1bfe8477faf524a3c65d&profile_id=174&oauth2_token_id=1284792283",
            "width": 1280,
            "height": 720
        },
        {
            "file": "https://player.vimeo.com/external/195521686.hd.mp4?s=133f40368eca3f16d5ff1bfe8477faf524a3c65d&profile_id=119&oauth2_token_id=1284792283",
            "width": 1920,
            "height": 1080
        },
        {
            "file": "https://player.vimeo.com/external/195521686.hd.mp4?s=133f40368eca3f16d5ff1bfe8477faf524a3c65d&profile_id=170&oauth2_token_id=1284792283",
            "width": 2560,
            "height": 1440
        },
        {
            "file": "https://player.vimeo.com/external/195521686.hd.mp4?s=133f40368eca3f16d5ff1bfe8477faf524a3c65d&profile_id=172&oauth2_token_id=1284792283",
            "width": 3840,
            "height": 2160
        }
    ],
    "last_watch_position_in_seconds": 0,
    "vimeo_video_id": "195521686",
    "youtube_video_id": null,
    "length_in_seconds": "603",
    "xp": 150,
    "total_xp": 150,
    "xp_bonus": 150,
    "published_on": "2019/10/10 20:40:07",
    "is_added_to_primary_playlist": true,
    "like_count": 181
}
```
