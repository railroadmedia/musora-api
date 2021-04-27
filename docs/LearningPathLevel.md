## Learning path level
Return learning path level content based on learning path's slug and level's slug.
The results respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-3d89f642-8030-4c9f-8c43-8a219179e14e"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/learning-path-levels/{methodSlug}/{levelSlug}`

### Permissions
    - Only authenticated user can access the endpoint

### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/learning-path-levels/drumeo-method/getting-started-on-the-drums',
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
  "id": 241248,
  "type": "learning-path-level",
  "title": "Getting Started On The Drums",
  "vimeo_video_id": "371752510",
  "youtube_video_id": null,
  "instructor": [
    {
      "id": 31880,
      "name": "Jared Falk",
      "biography": "<p>Jared Falk is a co-founder of Drumeo and author of the best-seller instructional programs &ldquo;Successful Drumming&rdquo; and &ldquo;Bass Drum Secrets&rdquo;. With over 15 years of experience teaching drummers from all over the world, Jared is known for his simplified teaching methods and high level of enthusiasm for the drumming community. He&rsquo;s a master of the heel-toe foot technique and a proficient rock/funk drummer, whose sole objective is making your experience behind a drum set, fulfilling and fun.</p>",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg"
    }
  ],
  "description": "Level 1 is where you’ll develop a rock-solid foundation for your drumming that will prepare you for all the other levels in The Drumeo Method and set you up for success for the rest of your drumming journey. Whether you want to be a rock, jazz, gospel, or blues drummer - to name only a few – the skills you’ll learn in this level will help you get there. You’re going to learn how to set up your drum-set, how to properly hold your drumsticks and play your pedals, and best of all, you’ll learn how to play two songs on the drums in no time! You will also start to develop your ears in this level so you can simply play what you hear. ",
  "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241248-card-thumbnail-1577144969.jpg",
  "mobile_app_url": "https://staging.drumeo.com/laravel/public/api/members/learning-paths/drumeo-method/getting-started-on-the-drums",
  "level_number": 1,
  "banner_background_image": "https://d1923uyy6spedc.cloudfront.net/241247-header-image-1583450010.jpg",
  "total_xp": "8927",
  "started": false,
  "completed": true,
  "progress_percent": 100,
  "video_playback_endpoints": [
    {
      "file": "https://player.vimeo.com/external/371752510.sd.mp4?s=a3ea08da6afc7dcf62401385d18f87ae238c2161&profile_id=139&oauth2_token_id=1284792283",
      "width": 426,
      "height": 240
    },
    {
      "file": "https://player.vimeo.com/external/371752510.sd.mp4?s=a3ea08da6afc7dcf62401385d18f87ae238c2161&profile_id=164&oauth2_token_id=1284792283",
      "width": 640,
      "height": 360
    },
    {
      "file": "https://player.vimeo.com/external/371752510.sd.mp4?s=a3ea08da6afc7dcf62401385d18f87ae238c2161&profile_id=165&oauth2_token_id=1284792283",
      "width": 960,
      "height": 540
    },
    {
      "file": "https://player.vimeo.com/external/371752510.hd.mp4?s=6124562ff1df19f875fefef446f0da42037c073e&profile_id=174&oauth2_token_id=1284792283",
      "width": 1280,
      "height": 720
    },
    {
      "file": "https://player.vimeo.com/external/371752510.hd.mp4?s=6124562ff1df19f875fefef446f0da42037c073e&profile_id=175&oauth2_token_id=1284792283",
      "width": 1920,
      "height": 1080
    },
    {
      "file": "https://player.vimeo.com/external/371752510.hd.mp4?s=6124562ff1df19f875fefef446f0da42037c073e&profile_id=170&oauth2_token_id=1284792283",
      "width": 2560,
      "height": 1440
    },
    {
      "file": "https://player.vimeo.com/external/371752510.hd.mp4?s=6124562ff1df19f875fefef446f0da42037c073e&profile_id=172&oauth2_token_id=1284792283",
      "width": 3840,
      "height": 2160
    }
  ],
  "length_in_seconds": "93",
  "banner_button_url": "",
  "is_added_to_primary_playlist": true,
  "published_on": "2019/12/27 08:00:00",
  "next_lesson": null,
  "courses": [
    {
      "id": 241249,
      "title": "Gear",
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-courses/241249",
      "level_rank": "1.1",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241249-card-thumbnail-1593029955.png",
      "is_added_to_primary_playlist": false,
      "progress_percent": 100
    },
    {
      "id": 241260,
      "title": "Technique",
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-courses/241260",
      "level_rank": "1.2",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241260-card-thumbnail-1593030100.png",
      "is_added_to_primary_playlist": true,
      "progress_percent": 100
    },
    {
      "id": 241266,
      "title": "Your First Song",
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-courses/241266",
      "level_rank": "1.3",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241266-card-thumbnail-1593030162.png",
      "is_added_to_primary_playlist": false,
      "progress_percent": 100
    },
    {
      "id": 241271,
      "title": "Your Second Song",
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-courses/241271",
      "level_rank": "1.4",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241271-card-thumbnail-1593030269.png",
      "is_added_to_primary_playlist": false,
      "progress_percent": 100
    },
    {
      "id": 241278,
      "title": "Ears",
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-courses/241278",
      "level_rank": "1.5",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241278-card-thumbnail-1593030449.png",
      "is_added_to_primary_playlist": false,
      "progress_percent": 100
    }
  ]
}
```
