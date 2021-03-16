
## Pull in-progress contents

Get an array with content in progress that respect filters criteria.
The results are paginated and respect the response structure defined in musora-api config file.

### HTTP Request
`GET musora-api/in-progress`


### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-4c4fdfd3-fd0d-4c94-94bd-40c2fa218ffa)

### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  sort                             |  no       |  'published_on'  |  Defaults to ascending order; to switch to descending order put a minus sign (-) in front of the value. Can be any of the following: slug; status; type; brand; language; position; parent_id; published_on; created_on; archived_on and progress                                           |
| query           |  included_types                   |  no       |  []              |  Only contents with these types will be returned.                                                                                                                                                                                                                                    |
| query           |  statuses                         |  no       |  'published'     |  All content must have one of these statuses.                                                                                                                                                                                                                                   |
| query           |  filter[required_fields]          |  no       |  []              |  All returned contents are required to have this field. Value format is: key;value;type (type is optional if its not declared all types will be included)                                                                                                                       |
| query           |  filter[included_fields]          |  no       |  []              |  Contents that have any of these fields will be returned. The first included field is the same as a required field but all included fields after the first act inclusively. Value format is: key value type (type is optional - if its not declared all types will be included) |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/in-progress?' +
        'page=1' + '&' +
        'limit=10' + '&' +
        'included_types[]=course' + '&' +
        'statuses[]=published' + '&' +
        'filter[required_fields][]=topic,rock,string' + '&' +
        'filter[included_fields][]=topic,jazz,string' + '&' +
        'filter[included_fields][]=difficulty,3,integer' + '&' +
        'filter[included_fields][]=difficulty,9',
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
      "id": 287865,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/287865-card-thumbnail-1614860513.png",
      "type": "quick-tips",
      "published_on": "2021/03/04 18:00:00",
      "status": "published",
      "title": "Drum Chops For Beginners",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "957",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    },
    {
      "id": 287342,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/287342-card-thumbnail-1614637391.png",
      "type": "question-and-answer",
      "published_on": "2021/02/23 01:00:00",
      "status": "published",
      "title": "Weekly Q&A",
      "completed": false,
      "started": true,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Aaron Edgar"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "4283",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "isLive": false
    }
  ],
  "meta": {
    "totalResults": 27,
    "page": "1",
    "limit": "2",
    "filterOptions": {
      "content_type": [
        "course",
        "pack-bundle-lesson",
        "podcasts",
        "question-and-answer",
        "quick-tips"
      ],
      "difficulty": [
        "All",
        "1",
        "10",
        "4",
        "5",
        "7",
        "All Skill Levels"
      ],
      "topic": [
        "All",
        "Beats",
        "Ear Training",
        "Edutainment",
        "Fills",
        "Gear",
        "Musicality",
        "Rudiments",
        "Styles",
        "Theory"
      ],
      "style": [
        "All"
      ],
      "instructor": [
        {
          "id": 31888,
          "name": "Aaron Edgar",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/aaron-edgar.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 273265,
          "name": "Rob Brown",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/273265-avatar-1605005582.png",
          "type": "instructor"
        },
        {
          "id": 206503,
          "name": "Seamus Evely",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/seamus-evely.jpg",
          "type": "instructor"
        }
      ],
      "showSkillLevel": true
    }
  }
}
```

