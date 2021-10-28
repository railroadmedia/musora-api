## Latest lessons
Get an array with the user's subscribed coaches latest content or all coaches latest content if the user is not subscribed to any coaches
The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/followed-lessons?brand={brand}&limit={limit}&page={page}`


### Permissions
    - Only authenticated user can access the endpoint


### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  content_type                     |  no       |                  |  Only followed contents with this type will be returned.                                                                                                                                                                                                                                    |
| query           |  brand                            |  no       |  config default value              |  Only contents with the brand will be returned.                                                                                                                                                                                                                                    |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/followed-lessons?' +
        'page=1' + '&' +
        'limit=1' + '&',
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
  "data": [
    {
      "id": 321769,
      "popularity": null,
      "thumbnail_url": null,
      "type": "performances",
      "published_on": "2021/10/10 15:00:00",
      "status": "published",
      "title": "Chris Fischer - \"Dharma Express\"",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 0,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/321769",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/321769",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 321425,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/1920x1080-YT-3-1633011695.png",
      "type": "performances",
      "published_on": "2021/10/01 15:00:00",
      "status": "published",
      "title": "World's Longest Fill - \"Wipeout\" Edition",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "322",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 29,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/321425",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/321425",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 319992,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/PA-ivory-tower-1632475616.png",
      "type": "play-along",
      "published_on": "2021/09/26 15:00:00",
      "status": "published",
      "title": "Ivory Tower",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Jared Falk"
      ],
      "artist": "Chris Fischer",
      "style": "Funk",
      "length_in_seconds": "217",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 5,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/319992",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/319992",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 319990,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/perf-JF-ivory-tower-1632135986.png",
      "type": "performances",
      "published_on": "2021/09/26 15:00:00",
      "status": "published",
      "title": "Chris Fischer - \"Ivory Tower\"",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "217",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 17,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/319990",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/319990",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 306580,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/coach-jf-drummers-on-instagram-you-should-follow-1631568848.png",
      "type": "coach-stream",
      "published_on": "2021/09/25 18:00:00",
      "status": "published",
      "title": "Drummers On Instagram You Should Follow",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "3765",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false,
      "like_count": 16,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/api/content/306580",
      "live_event_start_time": "2021/09/25 18:00:00",
      "live_event_end_time": "2021/09/25 19:15:00"
    },
    {
      "id": 320122,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/2-mil-subs-live-celebration-TH-1632400074.png",
      "type": "live",
      "published_on": "2021/09/23 21:30:00",
      "status": "published",
      "title": "Celebrating 2 Million Youtube Subscribers",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Dave Atkinson",
        "Jared Falk",
        "Dave Atkinson"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "6498",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false,
      "like_count": 2,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/320122",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/320122",
      "live_event_start_time": "2021/09/23 21:30:00",
      "live_event_end_time": "2021/09/23 23:45:00"
    },
    {
      "id": 319715,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/1920x1080-YT-1(4)-1631882798.png",
      "type": "performances",
      "published_on": "2021/09/17 16:00:00",
      "status": "published",
      "title": "Making Household Items Sound Like A Professional Drumset",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Victor Guidera",
        "Jared Falk",
        "Victor Guidera"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "667",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 25,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/319715",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/319715",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 306581,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/coach-jf-upcoming-live-stream-1631013960.png",
      "type": "coach-stream",
      "published_on": "2021/09/11 18:00:00",
      "status": "published",
      "title": "How I Create My Online Drum Lessons",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "3877",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false,
      "like_count": 9,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/api/content/306581",
      "live_event_start_time": "2021/09/11 18:00:00",
      "live_event_end_time": "2021/09/11 19:15:00"
    },
    {
      "id": 318877,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/1920x1080-YT-1(4)-1631279725.png",
      "type": "performances",
      "published_on": "2021/09/10 15:00:00",
      "status": "published",
      "title": "Every Drummer Does This!",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "280",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 40,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/318877",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/318877",
      "live_event_start_time": null,
      "live_event_end_time": null
    },
    {
      "id": 318327,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/PA-iconolastik-1631018074.png",
      "type": "play-along",
      "published_on": "2021/09/08 15:00:00",
      "status": "published",
      "title": "Iconolastik",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk",
        "Jared Falk"
      ],
      "artist": "Chris Fischer",
      "style": "Pop/Rock",
      "length_in_seconds": "244",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 8,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/318327",
      "musora_api_mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/content/318327",
      "live_event_start_time": null,
      "live_event_end_time": null
    }
  ],
  "meta": {
    "totalResults": 616,
    "page": 1,
    "limit": 10,
    "filterOptions": {
      "content_type": [
        "25-days-of-christmas",
        "assignment",
        "behind-the-scenes",
        "boot-camps",
        "challenges",
        "coach-stream",
        "course",
        "gear-guides",
        "learning-path-course",
        "learning-path-lesson",
        "learning-path-level",
        "live",
        "on-the-road",
        "pack",
        "pack-bundle",
        "pack-bundle-lesson",
        "paiste-cymbals",
        "performances",
        "play-along",
        "podcasts",
        "question-and-answer",
        "quick-tips",
        "semester-pack",
        "semester-pack-lesson",
        "song",
        "sonor-drums",
        "student-focus",
        "tama-drums"
      ],
      "topic": [
        "All",
        "Beats",
        "Ear Training",
        "Edutainment",
        "Fills",
        "Funk",
        "Gear",
        "Independence",
        "Musicality",
        "Odd Time",
        "Performance",
        "Performances",
        "Rudiments",
        "Styles",
        "Technique",
        "Theory"
      ],
      "difficulty": [
        "All",
        "All Skill Levels",
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "10"
      ],
      "style": [
        "All",
        "Blues",
        "Electronic",
        "Funk",
        "Jazz",
        "Latin",
        "Metal",
        "Odd Time",
        "Pop/Rock",
        "R&B",
        "World"
      ],
      "bpm": [
        "All",
        "80",
        "98",
        "100",
        "102",
        "105",
        "106",
        "110",
        "114",
        "115",
        "125",
        "127",
        "128",
        "129",
        "130",
        "140",
        "145",
        "147",
        "150",
        "155",
        "158",
        "162",
        "165",
        "170",
        "171",
        "174",
        "180",
        "182"
      ],
      "artist": [
        "All",
        "Chris Fischer",
        "Chris Fisher",
        "Craig Zurba",
        "Foo Fighters",
        "Green Day",
        "Lenny Kravitz",
        "Lynyrd Skynyrd",
        "Nate Savage",
        "Queens Of The Stone Age",
        "Sterr"
      ],
      "instructor": [
        {
          "id": 31888,
          "name": "Aaron Edgar",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/aaron-edgar.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 247864,
          "name": "Alex Rüdinger",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/247864-avatar-1584356276.jpg",
          "type": "instructor"
        },
        {
          "id": 31920,
          "name": "Anika Nilles",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/anika-nilles.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 255287,
          "name": "Aquiles Priester",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/255287-avatar-1588774393.jpg",
          "type": "instructor"
        },
        {
          "id": 233797,
          "name": "LJ \"BabyBoyDrummer\" Wilson",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/233797-avatar-1570486866.jpg",
          "type": "instructor"
        },
        {
          "id": 31908,
          "name": "Benny Greb",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/benny-greb.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 191327,
          "name": "Brandon Khoo",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/brandon-khoo.png",
          "type": "instructor"
        },
        {
          "id": 31959,
          "name": "Brandon Toews",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31959-avatar-1560447202.jpg",
          "type": "instructor"
        },
        {
          "id": 31952,
          "name": "Brian Frasier-Moore",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/brian-frasier-moore.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 31964,
          "name": "Bruce Becker",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/1514589819-bruce.jpg",
          "type": "instructor"
        },
        {
          "id": 212504,
          "name": "Carson Gant",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/carson-gant.jpg",
          "type": "instructor"
        },
        {
          "id": 31905,
          "name": "Casey Cooper",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/casey-cooper.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 251228,
          "name": "Chris Coleman",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/251228-avatar-1586264508.jpg",
          "type": "instructor"
        },
        {
          "id": 31934,
          "name": "Chuck Silverman",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/chuck-silverman.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg",
          "type": "instructor"
        },
        {
          "id": 212107,
          "name": "Dom Famularo",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dom-famularo.jpg",
          "type": "instructor"
        },
        {
          "id": 234095,
          "name": "Dorothea Taylor",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/234095-avatar-1570448168.jpg",
          "type": "instructor"
        },
        {
          "id": 257517,
          "name": "Ghost-Note",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/257517-avatar-1590271461.jpg",
          "type": "instructor"
        },
        {
          "id": 237359,
          "name": "Glen Sobel",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/237359-avatar-1574076692.jpg",
          "type": "instructor"
        },
        {
          "id": 215078,
          "name": "Harry Miree",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/harry-miree-1.jpg",
          "type": "instructor"
        },
        {
          "id": 252399,
          "name": "Horacio Hernandez",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/252399-avatar-1586861696.jpg",
          "type": "instructor"
        },
        {
          "id": 31880,
          "name": "Jared Falk",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg",
          "type": "instructor"
        },
        {
          "id": 281910,
          "name": "Jared Falk",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281910-avatar-1609277499.png",
          "type": "coach"
        },
        {
          "id": 196848,
          "name": "Jason McGerr",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/jason-mcgerr.jpg",
          "type": "instructor"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 207124,
          "name": "Jojo Mayer",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/jojo-mayer.jpg",
          "type": "instructor"
        },
        {
          "id": 230446,
          "name": "Juan Mendoza",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/230446-avatar-1565604177.jpg",
          "type": "instructor"
        },
        {
          "id": 202289,
          "name": "Kaz Rodriguez",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/kaz-rodriguez-1.jpg",
          "type": "instructor"
        },
        {
          "id": 31885,
          "name": "Kyle Radomsky",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/kyle-radomsky.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 31895,
          "name": "Larnell Lewis",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/larnell-lewis.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 314119,
          "name": "Lisa Witt",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/305432-avatar-1625835028.jpg",
          "type": "instructor"
        },
        {
          "id": 220735,
          "name": "Marco Minnemann",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/marco-minnemann-thumb.jpg",
          "type": "instructor"
        },
        {
          "id": 195923,
          "name": "Mark Guiliana",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/mark-guiliana.jpg",
          "type": "instructor"
        },
        {
          "id": 31922,
          "name": "Mark Kelso",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/mark-kelso.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 31878,
          "name": "Mike Michalkow",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/mike-michalkow.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 203172,
          "name": "Nick D'Virgilio",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/nick-dvirgilio.jpg",
          "type": "instructor"
        },
        {
          "id": 31913,
          "name": "Reuben Spyker",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/reuben-spyker.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 202287,
          "name": "Sarah Thawer",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/sarah-thawer.jpg",
          "type": "instructor"
        },
        {
          "id": 206503,
          "name": "Seamus Evely",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/seamus-evely.jpg",
          "type": "instructor"
        },
        {
          "id": 31932,
          "name": "Sean Browne",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/sean-browne.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 31884,
          "name": "Sean Lang",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/sean-lang.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 214093,
          "name": "Stan Bicknell",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/stan-bicknell-1.jpg",
          "type": "instructor"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg",
          "type": "instructor"
        },
        {
          "id": 253573,
          "name": "Steve Smith",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/253573-avatar-1587653589.jpg",
          "type": "instructor"
        },
        {
          "id": 203867,
          "name": "Tommy Igoe",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/tommy-igoe-1.jpg",
          "type": "instructor"
        },
        {
          "id": 220741,
          "name": "Tony Coleman",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220741-avatar-1555324221.jpg",
          "type": "instructor"
        },
        {
          "id": 31915,
          "name": "Victor Guidera",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/victor-guidera.png?v=1513185407",
          "type": "instructor"
        }
      ],
      "showSkillLevel": true
    }
  }
}
```
