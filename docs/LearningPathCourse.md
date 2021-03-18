## Learning path course
Return course content based on course id.

If `download` parameter exists on the request, extra data are returned for the offline mode.

The results respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-540650eb-e7e2-42ea-becd-7ba891274f89"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/learning-path-courses/{courseId}`

### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key        |  required |  description                       |
|-----------------|--------------|-----------|------------------------------------|
| path            |  id          |  yes      |  Id of the course you want to pull |
| query           |  download    |  no       |  Add extra data for the offline mode.  |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/learning-path-courses/241249',
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
  "id": 241249,
  "type": "learning-path-course",
  "is_added_to_primary_playlist": false,
  "started": false,
  "completed": true,
  "title": "Gear",
  "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-courses/241249",
  "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241249-card-thumbnail-1593029955.png",
  "description": "In this course, you'll learn about all the different parts of your drum-set. You'll learn the name of each piece of drum equipment, the function and purpose of each piece, and how to set everything up properly and efficiently. ",
  "progress_percent": 100,
  "xp": "2250",
  "total_xp": "2250",
  "banner_button_url": "",
  "published_on": "2019/12/20 23:07:19",
  "like_count": 5,
  "total_length_in_seconds": 5592,
  "level_position": 1,
  "course_position": 1,
  "instructor": [
    {
      "id": 31880,
      "name": "Jared Falk",
      "biography": "<p>Jared Falk is a co-founder of Drumeo and author of the best-seller instructional programs &ldquo;Successful Drumming&rdquo; and &ldquo;Bass Drum Secrets&rdquo;. With over 15 years of experience teaching drummers from all over the world, Jared is known for his simplified teaching methods and high level of enthusiasm for the drumming community. He&rsquo;s a master of the heel-toe foot technique and a proficient rock/funk drummer, whose sole objective is making your experience behind a drum set, fulfilling and fun.</p>",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg"
    }
  ],
  "next_lesson": null,
  "lessons": [
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
  ]
}
```
