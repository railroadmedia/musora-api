## Full text search
Full text search in contents.
The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-52155aec-b54a-45d9-ab81-48d2e784869b"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/search`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  sort                             |  no       |  '-score'  |  Defaults to ascending order; to switch to descending order put a minus sign (-) in front of the value. Can be any of the following: slug; status; type; brand; language; position; parent_id; published_on; created_on; archived_on and progress                                           |
| query           |  included_types                   |  no       |  []              |  Only contents with these types will be returned.                                                                                                                                                                                                                                    |
| query           |  statuses                         |  no       |  'published'     |  All content must have one of these statuses.                                                                                                                                                                                                                                   |
| query           |  brand          |  no       |                |  All returned contents are required to have this field. Value format is: key;value;type (type is optional if its not declared all types will be included)                                                                                                                       |
| query           |  term         |  yes       |                |  Search criteria |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/aearch?' +
        'page=1' + '&' +
        'limit=1' + '&' +
        'term=practice', 
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
      "id": 215667,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/e03-how-to-find-your-motivation-low.jpg",
      "type": "sonor-drums",
      "published_on": "2018/11/27 18:00:00",
      "status": "published",
      "title": "How To Find Your Motivation",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": "All",
      "length_in_seconds": "461",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 22
    },
    {
      "id": 243206,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/243206-card-thumbnail-1593031952.png",
      "type": "learning-path-lesson",
      "published_on": "2020/01/31 22:00:00",
      "status": "published",
      "title": "Basic Punk Play-Along",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Mike Michalkow"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "375",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 146
    }
  ],
  "meta": {
    "totalResults": 954,
    "page": "1",
    "limit": "2",
    "filterOptions": {
      "showSkillLevel": true
    }
  }
}
```
