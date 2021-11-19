## Pull content

Get content data based on content id.

For the content with `coach` type, the endpoint return the associated lessons in the response also, with filter feature.


If `download` parameter exists on the request, extra data are returned for the offline mode.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-50078eda-4039-4084-ba6d-d527a332deb8"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/content/{id}`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key                              |  required   |  description                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| path            |  id                             |  yes         |  Id of the content you want to pull                                                                                                                                                                                |
| query           |  statuses                         |  no       |  'published'     |  Only for coach: All coach lessons must have one of these statuses.                                                                                                                                                                                                                                   |
| query           |  filter[required_user_states]     |  no       |  []              |  Only for coach: All returned lessons are required to have these states for the authenticated user. Value format is: state                                                                                                                                                                     |
| query           |  filter[included_user_states]     |  no       |  []              |  Only for coach: All returned lessons that have any of these states for the authenticated user will be returned. The first included user state is the same as a required user state but all included states after the first act inclusively. Value format is: state.                                        |
| query           |  download    |  no       |               |  Add extra data for offline mode.                                       |
| query           |  sort                             |  no       |  'published_on'  |  Only for coach in order to sort coach lessons. Defaults to ascending order; to switch to descending order put a minus sign (-) in front of the value. Can be any of the following: slug; status; type; brand; language; position; parent_id; published_on; created_on; archived_on and progress        
| query           |  page                             |  no       |  1              |  Only for coach for coach lessons pagination. Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  Only for coach for coach's lessons pagination. The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                 
### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/content/292038',
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
    "id": 292038,
    "type": "course-part",
    "published_on": "2021/02/20 16:00:00",
    "completed": false,
    "started": true,
    "progress_percent": 0,
    "is_added_to_primary_playlist": false,
    "title": "Doubles & Quads",
    "length_in_seconds": "424",
    "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292038-card-thumbnail-1613767045.png",
    "total_comments": 4,
    "comments": [
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 336733,
            "id": 177248,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/171631_1564144539931.jpg",
                "xp": 0,
                "display_name": "Adam Ebihara",
                "xp_level": "Enthusiast I"
            },
            "comment": "Awesome lesson because Ash makes what sounds like complicated drumming -- to the ears of this beginner -- very accessible and it sounds so good.  The clarity of the presentation is also really great. ",
            "created_on": "2021-03-04 12:48:10"
        },
        {
            "is_liked": false,
            "like_count": 0,
            "user_id": 311962,
            "id": 174511,
            "replies": [],
            "user": {
                "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/IMG_0201.jpg",
                "xp": "0",
                "display_name": "Adam Block",
                "xp_level": "Enthusiast I"
            },
            "comment": "Off topic question: What splash is that?",
            "created_on": "2021-02-21 22:57:30"
        },
        {
            "is_liked": false,
            "like_count": 1,
            "user_id": 150289,
            "id": 174400,
            "replies": [
                {
                    "id": 174465,
                    "content_id": 292038,
                    "comment": "What do you mean by balance exactly?  If physical balance makes no difference you can set them up identically.  Sound balance - as Aaron said they can tune the two drums differently but some like that they can tune them identical also, with a single kick drum the two beaters hit in different spots on the head and can make a slightly different sound.",
                    "parent_id": 174400,
                    "user_id": 149883,
                    "conversation_status": "open",
                    "display_name": "",
                    "created_on": "2021-02-21 19:56:32",
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
                "fields.profile_picture_image_url": "https://drumeo-user-avatars.s3-us-west-2.amazonaws.com/116587_avatar_url_1464799497.jpg",
                "xp": 0,
                "display_name": "572heaven",
                "xp_level": "Enthusiast I"
            },
            "comment": "thank you Aron \n \nI guess my real question is \nDo you think its easier to achive good balance with a  2 bass drum set up  . Other then the cool look factor I am assuming metal band drummers use double bass drums for a reason. ",
            "created_on": "2021-02-21 15:19:36"
        },
        {
            "is_liked": false,
            "like_count": 1,
            "user_id": 150289,
            "id": 174199,
            "replies": [
                {
                    "id": 174335,
                    "content_id": 292038,
                    "comment": "I know this question was meant for Ash, but I can answer from experience of playing on a kit with two bass drums and having drummer friends who do. Advantages of playing two bass drums:you can tune each drum differently to sound unique from each other you can mic them individually You can also have two differently sized bass drums as well. You play with individual pedals = even feelThe advantage of having a double pedal:portability and more space Get a really concise and even sound Sound can be altered by changing the type of beater you use. Regarding balance, if you're talking about movement and body positioning, it helps to start like you would initially setup your kit. You can take your throne and move it away from your kit and just sit down comfortably. Wherever your feet are naturally positioned should be where your pedals are located.Seat height does play a big role in your balance as well, if you're too high you could have some imbalance in your movement and staiblity when you turn or lean. The idea is to keep your legs remained in position and when movement is involved focus on how it feels to move your torso when you have to reposition yourself to play a certain drum, for instance the floor tom. I hope this helps/informs you in some way.",
                    "parent_id": 174199,
                    "user_id": 149869,
                    "conversation_status": "open",
                    "display_name": "",
                    "created_on": "2021-02-21 08:58:06",
                    "deleted_at": null,
                    "like_count": 4,
                    "like_users": [
                        {
                            "id": 415949,
                            "display_name": "Susan T.",
                            "avatar_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/415949_1614082514027-1614082524-415949.jpg"
                        },
                        {
                            "id": 345119,
                            "display_name": "Hexagram",
                            "avatar_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/345119_1605801373601-1605801376-345119.jpg"
                        },
                        {
                            "id": 322757,
                            "display_name": "grzegorz",
                            "avatar_url": "https://s3.amazonaws.com/pianote/defaults/avatar.png"
                        }
                    ],
                    "is_liked": false,
                    "user": {
                        "display_name": "Aaron L - Linkmaster",
                        "xp": "0",
                        "xp_level": "Enthusiast I",
                        "access_level": "lifetime",
                        "fields.profile_picture_image_url": "https://dzryyo1we6bm3.cloudfront.net/avatars/avatar-1610723867-149869.jpg",
                        "level_number": "1.0",
                        "isAdmin": false
                    }
                }
            ],
            "user": {
                "fields.profile_picture_image_url": "https://drumeo-user-avatars.s3-us-west-2.amazonaws.com/116587_avatar_url_1464799497.jpg",
                "xp": 0,
                "display_name": "572heaven",
                "xp_level": "Enthusiast I"
            },
            "comment": "Love it Question for AshWhat are the advantages of having double bass drums rather then single bass drum with double pedals? I have a single bass drum with double pedals. I am happy with my improvement but I feel Like I am always fighting a balance issue as I move around the kit playing hand foot metal combos ",
            "created_on": "2021-02-20 19:32:50"
        }
    ],
    "xp": 200,
    "xp_bonus": "150",
    "instructor": [
        {
            "id": 31896,
            "name": "Ash Pearson",
            "biography": "Ash Pearson is the drummer for heavy-metal band 3 Inches of Blood, and was formerly in Angel Grinder and Sound of Swarm. Passionate about music and a masterful metal drummer, Ash is serious about his craft, learning as much about other styles of music and drumming as possible. He loves sharing his knowledge about music and drums, having been teaching for years as a clinician and private instructor.",
            "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/ash-pearson.png?v=1513185407"
        }
    ],
    "vimeo_video_id": null,
    "youtube_video_id": "x1rg6j4kff0",
    "description": "In this lesson, Ash teaches some two-note and two-note patterns using all your limbs. These are classic metal fills that are fun to play! ",
    "captions": null,
    "like_count": 32,
    "user_progress": {
        "149628": {
            "id": 14979506,
            "content_id": 292038,
            "user_id": 149628,
            "state": "started",
            "progress_percent": 0,
            "higher_key_progress": null,
            "updated_on": "2021-03-09 14:11:46"
        }
    },
    "resources": [
        {
            "resource_id": 171604,
            "resource_name": "PDF Sheet Music",
            "resource_url": "https://s3.amazonaws.com/drumeo/courses/pdf/dcb-106-metal-drum-fills/dcb-106b-doubles-and-quads.pdf"
        },
        {
            "resource_id": 171679,
            "resource_name": "Course Resources Pack",
            "resource_url": "https://s3.amazonaws.com/drumeo/courses/resource-files/dcb-106-heavy-metal-drum-fills.zip"
        }
    ],
    "assignments": [
        {
            "id": 292056,
            "xp": 25,
            "user_progress": {
                "149628": []
            },
            "title": "#1",
            "soundslice_slug": "dbpfc",
            "description": null,
            "sheet_music_image_url": [
                {
                    "id": 171606,
                    "content_id": 292056,
                    "key": "sheet_music_image_url",
                    "value": "https://d1923uyy6spedc.cloudfront.net/292056-sheet-image-1613755255.svg",
                    "position": 1
                }
            ],
            "timecode": null
        },
        {
            "id": 292057,
            "xp": 25,
            "user_progress": {
                "149628": []
            },
            "title": "#2",
            "soundslice_slug": "Wbpfc",
            "description": null,
            "sheet_music_image_url": [
                {
                    "id": 171607,
                    "content_id": 292057,
                    "key": "sheet_music_image_url",
                    "value": "https://d1923uyy6spedc.cloudfront.net/292057-sheet-image-1613755336.svg",
                    "position": 1
                }
            ],
            "timecode": null
        }
    ],
    "related_lessons": [
        {
            "id": 292037,
            "type": "course-part",
            "published_on": "2021/02/20 16:00:00",
            "completed": false,
            "started": true,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "title": "What Makes A Fill A Heavy Metal Drum Fill?",
            "length_in_seconds": "92",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292037-card-thumbnail-1613767181.png"
        },
        {
            "id": 292039,
            "type": "course-part",
            "published_on": "2021/02/20 16:00:00",
            "completed": false,
            "started": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "title": "8th Note Triplet & 16th Note Triplet Applications",
            "length_in_seconds": "532",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292039-card-thumbnail-1613766853.png"
        },
        {
            "id": 292040,
            "type": "course-part",
            "published_on": "2021/02/20 16:00:00",
            "completed": false,
            "started": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "title": "Over-The-Barline Ideas",
            "length_in_seconds": "223",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292040-card-thumbnail-1613766711.png"
        },
        {
            "id": 292042,
            "type": "course-part",
            "published_on": "2021/02/20 16:00:00",
            "completed": false,
            "started": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "title": "32nd Note Applications",
            "length_in_seconds": "261",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292042-card-thumbnail-1613766532.png"
        },
        {
            "id": 292043,
            "type": "course-part",
            "published_on": "2021/02/20 16:00:00",
            "completed": false,
            "started": false,
            "progress_percent": 0,
            "is_added_to_primary_playlist": false,
            "title": "Conclusion",
            "length_in_seconds": "158",
            "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292043-card-thumbnail-1613766626.png"
        }
    ],
    "next_lesson": {
        "id": 292039,
        "type": "course-part",
        "published_on": "2021/02/20 16:00:00",
        "completed": false,
        "started": false,
        "progress_percent": 0,
        "is_added_to_primary_playlist": false,
        "title": "8th Note Triplet & 16th Note Triplet Applications",
        "length_in_seconds": "532",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292039-card-thumbnail-1613766853.png"
    },
    "previous_lesson": {
        "id": 292037,
        "type": "course-part",
        "published_on": "2021/02/20 16:00:00",
        "completed": false,
        "started": true,
        "progress_percent": 0,
        "is_added_to_primary_playlist": false,
        "title": "What Makes A Fill A Heavy Metal Drum Fill?",
        "length_in_seconds": "92",
        "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292037-card-thumbnail-1613767181.png"
    },
    "last_watch_position_in_seconds": 0
}
```
