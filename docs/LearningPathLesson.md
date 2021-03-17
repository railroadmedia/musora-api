## Learning path lesson
Return lesson content based on lesson id.
The results respect the response structure defined in musora-api config file.

### HTTP Request
`GET musora-api/learning-path-lessons/{lessonId}`

### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-7ea54dcf-88ad-418e-b903-2d280d2eecda)


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/learning-path-lessons/241250',
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
    "id": 241250,
    "type": "learning-path-lesson",
    "title": "The Gear In Front Of You",
    "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241250",
    "length_in_seconds": "708",
    "vimeo_video_id": "372739383",
    "youtube_video_id": null,
    "instructor": [
        {
            "id": 31880,
            "name": "Jared Falk",
            "biography": "Jared Falk is a co-founder of Drumeo and author of the best-seller instructional programs “Successful Drumming” and “Bass Drum Secrets”. With over 15 years of experience teaching drummers from all over the world, Jared is known for his simplified teaching methods and high level of enthusiasm for the drumming community. He’s a master of the heel-toe foot technique and a proficient rock/funk drummer, whose sole objective is making your experience behind a drum set, fulfilling and fun.",
            "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg"
        }
    ],
    "description": "In this lesson, you will learn about all the gear used in a standard drum-set.",
    "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241250-card-thumbnail-1593029823.png",
    "total_xp": "175",
    "xp": "175",
    "user_progress": {
        "149628": {
            "id": 14979502,
            "content_id": 241250,
            "user_id": 149628,
            "state": "completed",
            "progress_percent": 100,
            "higher_key_progress": null,
            "updated_on": "2021-03-12 09:20:33"
        }
    },
    "published_on": "2019/12/20 23:07:20",
    "is_last_incomplete_lesson_from_course": true,
    "next_lesson": {
        "id": 241251,
        "type": "learning-path-lesson",
        "published_on": "2019/12/20 17:00:00",
        "completed": true,
        "started": false,
        "progress_percent": 100,
        "is_added_to_primary_playlist": false,
        "title": "Setting Up Your Space",
        "length_in_seconds": "213",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241251-card-thumbnail-1593029841.png",
        "status": "published",
        "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241251"
    },
    "prev_lesson": null,
    "current_level": {
        "id": 241248,
        "title": "Getting Started On The Drums",
        "xp": "1000",
        "level_number": 1,
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241248-card-thumbnail-1577144969.jpg"
    },
    "next_level": {
        "id": 241284,
        "title": "Basic Theory & Ear Training",
        "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/basic-theory-ear-training",
        "level_number": 2,
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241284-card-thumbnail-1577144975.jpg",
        "published_on": "2020/01/31 22:00:00",
        "is_added_to_primary_playlist": false
    },
    "assignments": [
        {
            "id": 241676,
            "xp": 25,
            "user_progress": {
                "149628": {
                    "id": 14979537,
                    "content_id": 241676,
                    "user_id": 149628,
                    "state": "completed",
                    "progress_percent": 100,
                    "higher_key_progress": null,
                    "updated_on": "2021-03-12 09:20:35"
                }
            },
            "title": "Get To Know Your Gear",
            "soundslice_slug": null,
            "description": "1. Be familiar with each piece of drum gear.2. Know what each piece is used for.",
            "timecode": "673"
        }
    ],
    "is_liked_by_current_user": true,
    "like_count": 1063,
    "is_added_to_primary_playlist": false,
    "level_position": 1,
    "course_position": 1,
    "related_lessons": [
        {
            "id": 241250,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "The Gear In Front Of You",
            "length_in_seconds": "708",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241250-card-thumbnail-1593029823.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241250"
        },
        {
            "id": 241251,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 17:00:00",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "Setting Up Your Space",
            "length_in_seconds": "213",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241251-card-thumbnail-1593029841.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241251"
        },
        {
            "id": 241252,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": true,
            "title": "Setting Up Your Pedals & Throne",
            "length_in_seconds": "179",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241252-card-thumbnail-1593029853.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241252"
        },
        {
            "id": 241253,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "Setting Up Your Bass Drum, Snare & Toms",
            "length_in_seconds": "585",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241253-card-thumbnail-1593029867.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241253"
        },
        {
            "id": 241254,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "Placing Your Cymbals Around Your Kit",
            "length_in_seconds": "625",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241254-card-thumbnail-1593029876.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241254"
        },
        {
            "id": 241255,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "The Science Of Tuning Your Drums",
            "length_in_seconds": "318",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241255-card-thumbnail-1593029886.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241255"
        },
        {
            "id": 241256,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "Tuning Your Snare Drum",
            "length_in_seconds": "981",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241256-card-thumbnail-1593029896.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241256"
        },
        {
            "id": 241257,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "Tuning Your Bass Drum",
            "length_in_seconds": "566",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241257-card-thumbnail-1593029906.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241257"
        },
        {
            "id": 241258,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "Tuning Your Toms",
            "length_in_seconds": "984",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241258-card-thumbnail-1593029924.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241258"
        },
        {
            "id": 241259,
            "type": "learning-path-lesson",
            "published_on": "2019/12/20 23:07:20",
            "started": false,
            "completed": true,
            "progress_percent": 100,
            "is_added_to_primary_playlist": false,
            "title": "Adjusting Your Bass Drum Pedal",
            "length_in_seconds": "433",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241259-card-thumbnail-1593029936.png",
            "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/241259"
        }
    ],
    "comments": [
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 425269,
            "id": 176890,
            "replies": [
                {
                    "id": 176898,
                    "content_id": 241250,
                    "comment": "Congrats!",
                    "parent_id": 176890,
                    "user_id": 149883,
                    "conversation_status": "open",
                    "display_name": "",
                    "created_on": "2021-03-02 21:48:25",
                    "deleted_at": null,
                    "like_count": 0,
                    "like_users": [],
                    "is_liked": false
                }
            ],
            "user": {
                "fields.profile_picture_image_url": false,
                "xp": false,
                "display_name": false,
                "xp_level": false
            },
            "comment": "I started the drums lesson yesterday",
            "created_on": "2021-03-02 21:30:14"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 425269,
            "id": 176889,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": false,
                "xp": false,
                "display_name": false,
                "xp_level": false
            },
            "comment": "I started the drums lesson yesterday",
            "created_on": "2021-03-02 21:30:08"
        },
        {
            "is_liked": false,
            "like_count": 2,
            "user_id": 318337,
            "id": 174425,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://drumeo-user-avatars.s3-us-west-2.amazonaws.com/152877_avatar_url_1512458204.jpg",
                "xp": "0",
                "display_name": "Dave's Gone Drinking",
                "xp_level": "Enthusiast I"
            },
            "comment": "I feel like I'm watching a drum version of Mr. Rogers Neighborhood !!  Also, I noticed that Jared needed a stunt double for the setting up of the \"double\" pedal.  Nice job on the video guys",
            "created_on": "2021-02-21 17:21:51"
        },
        {
            "is_liked": false,
            "like_count": 1,
            "user_id": 424357,
            "id": 174321,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": "0",
                "display_name": "ericvalero238961",
                "xp_level": "Enthusiast I"
            },
            "comment": "I’ve been drumming for about 15 years. Figured why not go through and see what someone else’s take on the basics is? Happy to take in the whole curriculum.",
            "created_on": "2021-02-21 05:32:32"
        },
        {
            "is_liked": false,
            "like_count": 2,
            "user_id": 423835,
            "id": 172794,
            "replies": [
                {
                    "id": 172796,
                    "content_id": 241250,
                    "comment": "Awesome.  Most that I know (including myself) started at the first level, good to check them off and never know when some small nugget we missed in there.",
                    "parent_id": 172794,
                    "user_id": 149883,
                    "conversation_status": "open",
                    "display_name": "",
                    "created_on": "2021-02-15 22:37:53",
                    "deleted_at": null,
                    "like_count": 0,
                    "like_users": [],
                    "is_liked": false,
                    "user": {
                        "display_name": "Scotty A",
                        "xp": "0",
                        "xp_level": "Enthusiast I",
                        "access_level": "team",
                        "fields.profile_picture_image_url": "https://drumeo-user-avatars.s3-us-west-2.amazonaws.com/319_avatar_url_1464797919.jpg",
                        "level_number": "1.0",
                        "isAdmin": true
                    }
                }
            ],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": "0",
                "display_name": "gigarusso27362",
                "xp_level": "Enthusiast I"
            },
            "comment": "Hi there! I'm just starting now the Drumeo learning path.\nEven if I'm playing the drum for years, I prefer to take evey lessons.\nSo... starting from the first!\n \nSee you later guys!\nPasquale",
            "created_on": "2021-02-15 22:20:07"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 418285,
            "id": 172193,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/418285_1612428826467-1612428831-418285.jpg",
                "xp": "0",
                "display_name": "David Oakes",
                "xp_level": "Enthusiast I"
            },
            "comment": "I used to own an 18\" Paiste 402 Crash Ride. One of my favourite cymbals I ever owned. The 402 Paiste were so nice. Silver in colour too. The wash on the 18\" Crash Ride was so nice. Wish I still had them. ",
            "created_on": "2021-02-13 21:07:02"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 413610,
            "id": 170943,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/IMG_0002-1610991360-413610.jpg",
                "xp": "0",
                "display_name": "MattyD11",
                "xp_level": "Enthusiast I"
            },
            "comment": "Good teach for this guy also I’m only nine years old",
            "created_on": "2021-02-10 01:46:31"
        },
        {
            "is_liked": false,
            "like_count": 1,
            "user_id": 419632,
            "id": 169876,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": "0",
                "display_name": "lbflame3441421",
                "xp_level": "Enthusiast I"
            },
            "comment": "Good lesson, basics are important and most people tend to skip them",
            "created_on": "2021-02-06 19:42:46"
        },
        {
            "is_liked": false,
            "like_count": 1,
            "user_id": 401403,
            "id": 168390,
            "replies": [
                {
                    "id": 168397,
                    "content_id": 241250,
                    "comment": "Rad! :D ",
                    "parent_id": 168390,
                    "user_id": 5814,
                    "conversation_status": "open",
                    "display_name": "",
                    "created_on": "2021-02-02 19:34:51",
                    "deleted_at": null,
                    "like_count": 0,
                    "like_users": [],
                    "is_liked": false,
                    "user": {
                        "display_name": "AaronEdgarDrum",
                        "xp": "0",
                        "xp_level": "Enthusiast I",
                        "access_level": "team",
                        "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/5814_1541167036528.jpg",
                        "level_number": "1.0",
                        "isAdmin": true
                    }
                }
            ],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": "0",
                "display_name": "crosema2355568",
                "xp_level": "Enthusiast I"
            },
            "comment": "lol i'm 11 and I started in october.\n ",
            "created_on": "2021-02-02 19:15:23"
        },
        {
            "is_liked": false,
            "like_count": 1,
            "user_id": 277284,
            "id": 168360,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
                "xp": "0",
                "display_name": "kennyip@shaw.ca",
                "xp_level": "Enthusiast I"
            },
            "comment": "Ken,   Im 73 in a couple of weeks  Have taken many lessons from drumeo, for about 4 years, some Ive paid for, others free of charge.  Drumeo has taught me to be a much better drummer and a drummer that now understands his part in a song.  ",
            "created_on": "2021-02-02 17:00:58"
        }
    ],
    "video_playback_endpoints": [
        {
            "file": "https://player.vimeo.com/external/372739383.sd.mp4?s=0186cf6004560c75610d1e233da85fc567a8b754&profile_id=139&oauth2_token_id=1284792283",
            "width": 426,
            "height": 240
        },
        {
            "file": "https://player.vimeo.com/external/372739383.sd.mp4?s=0186cf6004560c75610d1e233da85fc567a8b754&profile_id=164&oauth2_token_id=1284792283",
            "width": 640,
            "height": 360
        },
        {
            "file": "https://player.vimeo.com/external/372739383.sd.mp4?s=0186cf6004560c75610d1e233da85fc567a8b754&profile_id=165&oauth2_token_id=1284792283",
            "width": 960,
            "height": 540
        },
        {
            "file": "https://player.vimeo.com/external/372739383.hd.mp4?s=52eda152429b1f16a95b5149cd2884df4a5d56b9&profile_id=174&oauth2_token_id=1284792283",
            "width": 1280,
            "height": 720
        },
        {
            "file": "https://player.vimeo.com/external/372739383.hd.mp4?s=52eda152429b1f16a95b5149cd2884df4a5d56b9&profile_id=175&oauth2_token_id=1284792283",
            "width": 1920,
            "height": 1080
        },
        {
            "file": "https://player.vimeo.com/external/372739383.hd.mp4?s=52eda152429b1f16a95b5149cd2884df4a5d56b9&profile_id=170&oauth2_token_id=1284792283",
            "width": 2560,
            "height": 1440
        },
        {
            "file": "https://player.vimeo.com/external/372739383.hd.mp4?s=52eda152429b1f16a95b5149cd2884df4a5d56b9&profile_id=172&oauth2_token_id=1284792283",
            "width": 3840,
            "height": 2160
        }
    ],
    "captions": [],
    "last_watch_position_in_seconds": 0,
    "is_last_incomplete_course_from_level": true
}
```
