## Reset authenticated user progress on content

Delete the content progress for the authenticated user.
Return the parent content if exists, empty response otherwise.

### HTTP Request
`PUT musora-api/reset`


### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-35138923-5c9d-4c79-9869-2bcb85625824)

### Request Parameters

| path\|query\|body|  key                |  required |  description           |
|------------------|---------------------|-----------|------------------------|
| query            |  content_id  |  yes      |  The content id on which we reset the user progress.


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/reset',
    type: 'put',
    dataType: 'json',
    data:{
        content_id:292039
    },
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
  "id": 283669,
  "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/283669-card-thumbnail-1613767216.png",
  "type": "course",
  "published_on": "2021/02/20 16:00:00",
  "status": "published",
  "title": "Heavy Metal Drum Fills",
  "completed": false,
  "started": true,
  "progress_percent": 0,
  "is_added_to_primary_playlist": false,
  "instructors": [
    "Ash Pearson"
  ],
  "description": "Looking to increase your fill vocabulary? Play some fills that are heavy? This course will teach you what makes a fill work in the metal style, you will learn a bunch of great fills and learn some ways to develop these ideas and make them your own heavy metal fills!",
  "xp": 1700,
  "xp_bonus": "500",
  "duration": 1690,
  "like_count": 1,
  "current_lesson": {
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
  "lessons": [
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
      "id": 292038,
      "type": "course-part",
      "published_on": "2021/02/20 16:00:00",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "title": "Doubles & Quads",
      "length_in_seconds": "424",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/292038-card-thumbnail-1613767045.png"
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
  "instructor": [
    {
      "id": 31896,
      "name": "Ash Pearson",
      "biography": "Ash Pearson is the drummer for heavy-metal band 3 Inches of Blood, and was formerly in Angel Grinder and Sound of Swarm. Passionate about music and a masterful metal drummer, Ash is serious about his craft, learning as much about other styles of music and drumming as possible. He loves sharing his knowledge about music and drums, having been teaching for years as a clinician and private instructor.",
      "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/ash-pearson.png?v=1513185407"
    }
  ]
}
```