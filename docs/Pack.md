## Packs
Return pack data.
The results respect the response structure defined in musora-api config file.

### HTTP Request
`GET musora-api/pack/{id}`

### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-b3a4dedc-a925-44a5-963f-8ac157fe12cd)


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/pack/234577',
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
  "id": 234577,
  "type": "semester-pack",
  "title": "Independence Made Easy",
  "description": "In this 26 video course, Jared Falk will help you improve your drumming independence, guiding you towards becoming a more musical drummer.",
  "url": "https://staging.drumeo.com/laravel/public/members/semester-packs/independence-made-easy-pack",
  "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/234577",
  "completed": false,
  "started": true,
  "progress_percent": 0,
  "is_owned": true,
  "thumbnail": "https://d1923uyy6spedc.cloudfront.net/234577-header-image-1603126132.jpg",
  "pack_logo": "https://d1923uyy6spedc.cloudfront.net/independence-made-easy.svg",
  "apple_product_id": "independence_made_easy_pack",
  "google_product_id": "independence_made_easy_pack",
  "lessons": [
    {
      "title": "Set Up Your Practice Space",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/pack-dvds/550/ime-01.jpg",
      "id": 234769,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234769",
      "length_in_seconds": "603",
      "is_added_to_primary_playlist": true
    },
    {
      "title": "Practice Planner",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/pack-dvds/550/ime-02.jpg",
      "id": 234768,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234768",
      "length_in_seconds": "217",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Practice Pad Warm-Ups",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/pack-dvds/550/ime-03.jpg",
      "id": 234767,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234767",
      "length_in_seconds": "441",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Right, Left, Bass",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208668_thumbnail_360p.jpg",
      "id": 234761,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234761",
      "length_in_seconds": "1140",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Right, Bass, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208669_thumbnail_360p.jpg",
      "id": 234755,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234755",
      "length_in_seconds": "788",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Bass, Right, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208670_thumbnail_360p.jpg",
      "id": 234749,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234749",
      "length_in_seconds": "1077",
      "is_added_to_primary_playlist": true
    },
    {
      "title": "Bass, Bass, Right",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208671_thumbnail_360p.jpg",
      "id": 234743,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234743",
      "length_in_seconds": "965",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Right, Bass, Bass",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208672_thumbnail_360p.jpg",
      "id": 234737,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234737",
      "length_in_seconds": "899",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Bass, Right, Bass",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208673_thumbnail_360p.jpg",
      "id": 234731,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234731",
      "length_in_seconds": "844",
      "is_added_to_primary_playlist": true
    },
    {
      "title": "Right, Bass, Left, Bass",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208674_thumbnail_360p.jpg",
      "id": 234725,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234725",
      "length_in_seconds": "980",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Bass, Right, Bass, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208675_thumbnail_360p.jpg",
      "id": 234719,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234719",
      "length_in_seconds": "1065",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Bass, Bass, Right, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208676_thumbnail_360p.jpg",
      "id": 234713,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234713",
      "length_in_seconds": "1014",
      "is_added_to_primary_playlist": true
    },
    {
      "title": "Right, Left, Bass, Bass",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208677_thumbnail_360p.jpg",
      "id": 234707,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234707",
      "length_in_seconds": "915",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Right, Bass, Bass, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208678_thumbnail_360p.jpg",
      "id": 234701,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234701",
      "length_in_seconds": "898",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Triplets - Left, Left, Bass",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208679_thumbnail_360p.jpg",
      "id": 234694,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234694",
      "length_in_seconds": "1061",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Triplets - Left, Bass, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208680_thumbnail_360p.jpg",
      "id": 234688,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234688",
      "length_in_seconds": "894",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Triplets - Bass, Left, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208681_thumbnail_360p.jpg",
      "id": 234682,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234682",
      "length_in_seconds": "974",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Triplets - Left, Bass, Bass",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208682_thumbnail_360p.jpg",
      "id": 234676,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234676",
      "length_in_seconds": "903",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Triplets - Bass, Bass, Left",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208683_thumbnail_360p.jpg",
      "id": 234670,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234670",
      "length_in_seconds": "820",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Right Foot Left Foot",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208684_thumbnail_360p.jpg",
      "id": 234661,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234661",
      "length_in_seconds": "1408",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Bossa Nova Foot Ostinato",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208685_thumbnail_360p.jpg",
      "id": 234652,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234652",
      "length_in_seconds": "1530",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Tumbao Foot Ostinato",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208686_thumbnail_360p.jpg",
      "id": 234643,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234643",
      "length_in_seconds": "1602",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Kick, Hi-Hat, Hi-Hat",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208687_thumbnail_360p.jpg",
      "id": 234634,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234634",
      "length_in_seconds": "1594",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Triplets Over Your Ostinato",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208688_thumbnail_360p.jpg",
      "id": 234624,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234624",
      "length_in_seconds": "1084",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Double Stroke Roll With Your Feet",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208689_thumbnail_360p.jpg",
      "id": 234614,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234614",
      "length_in_seconds": "1219",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Weak Hand Independence & Control",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208690_thumbnail_360p.jpg",
      "id": 234605,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234605",
      "length_in_seconds": "1235",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "Lead Hand Independence",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208691_thumbnail_360p.jpg",
      "id": 234596,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234596",
      "length_in_seconds": "1227",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "The Bass Drum Foot",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208692_thumbnail_360p.jpg",
      "id": 234587,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234587",
      "length_in_seconds": "1194",
      "is_added_to_primary_playlist": false
    },
    {
      "title": "The Bass Drum Foot Continued",
      "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/thumbnails/208693_thumbnail_360p.jpg",
      "id": 234578,
      "progress_percent": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234578",
      "length_in_seconds": "1579",
      "is_added_to_primary_playlist": false
    }
  ],
  "current_lesson_index": 0,
  "next_lesson": {
    "title": "Set Up Your Practice Space",
    "artist": null,
    "style": null,
    "thumbnail_url": "https://dzryyo1we6bm3.cloudfront.net/card-thumbnails/pack-dvds/550/ime-01.jpg",
    "id": 234769,
    "type": "semester-pack-lesson",
    "status": "published",
    "published_on": "2019/10/10 20:40:07",
    "parent_id": 234577,
    "completed": false,
    "progress_percent": 0,
    "is_added_to_primary_playlist": true,
    "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/lesson/234769",
    "length_in_seconds": "603"
  }
}
```
