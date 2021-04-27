## My list
Based on `state` request's parameter can return:
- the `contents added` to authenticated user's playlist if `state` not exists on the request
- the `completed contents` if `state` = `completed`
- the `in-progress contents` if `state` = `started`

The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-a4f8e2b8-5940-44cd-b6b8-33c68114d186"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/my-list`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key                              |  required |  default         |  description\|notes                                                                                                                                                                                                                                                             |
|-----------------|-----------------------------------|-----------|------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| query           |  state                             |  no       |                |  State value: 'started' or 'completed'. See method description for more details.                                                                                                                                                                              |
| query           |  page                             |  no       |  1              |  Which page in the result set to return. The amount of contents skipped is ((limit - 1) * page).                                                                                                                                                                                |
| query           |  limit                            |  no       |  10               |  The max amount of contents that can be returned. Can be 'null' for no limit.                                                                                                                                                                                                   |
| query           |  sort                             |  no       |  'published_on'  |  Defaults to ascending order; to switch to descending order put a minus sign (-) in front of the value. Can be any of the following: slug; status; type; brand; language; position; parent_id; published_on; created_on; archived_on and progress                                           |
| query           |  included_types                   |  no       |  []              |  Only contents with these types will be returned.                                                                                                                                                                                                                                    |
| query           |  statuses                         |  no       |  'published'     |  All content must have one of these statuses.                                                                                                                                                                                                                                   |
| query           |  filter[required_fields]          |  no       |  []              |  All returned contents are required to have this field. Value format is: key;value;type (type is optional if its not declared all types will be included)                                                                                                                       |
| query           |  filter[included_fields]          |  no       |  []              |  Contents that have any of these fields will be returned. The first included field is the same as a required field but all included fields after the first act inclusively. Value format is: key value type (type is optional - if its not declared all types will be included) |
| query           |  filter[required_user_states]     |  no       |  []              |  All returned contents are required to have these states for the authenticated user. Value format is: state                                                                                                                                                                     |
| query           |  filter[included_user_states]     |  no       |  []              |  Contents that have any of these states for the authenticated user will be returned. The first included user state is the same as a required user state but all included states after the first act inclusively. Value format is: state.                                        |



### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/my-list?' +
        'page=1' + '&' +
        'limit=2' + '&' +
        'state=completed' 
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
      "id": 241282,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241282-card-thumbnail-1593030503.png",
      "type": "learning-path-lesson",
      "published_on": "2019/12/20 23:07:22",
      "status": "published",
      "title": "Developing Your Sense Of Time 101",
      "completed": true,
      "started": false,
      "progress_percent": 100,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "769",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 351
    },
    {
      "id": 241283,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241283-card-thumbnail-1593030521.png",
      "type": "learning-path-lesson",
      "published_on": "2019/12/20 23:07:22",
      "status": "published",
      "title": "Playing Along To Music",
      "completed": true,
      "started": false,
      "progress_percent": 100,
      "is_added_to_primary_playlist": true,
      "instructors": [
        "Jared Falk"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": "568",
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null,
      "like_count": 506
    }
  ],
  "meta": {
    "totalResults": 39,
    "page": "1",
    "limit": "2",
    "filterOptions": {
      "content_type": [
        "course",
        "learning-path-course",
        "learning-path-lesson",
        "learning-path-level",
        "play-along",
        "solos"
      ],
      "difficulty": [
        "All",
        "1",
        "5",
        "All Skill Levels"
      ],
      "topic": [
        "All",
        "Beats",
        "Musicality",
        "Rudiments",
        "Solos"
      ],
      "style": [
        "All",
        "Pop/Rock"
      ],
      "instructor": [
        {
          "id": 31959,
          "name": "Brandon Toews",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31959-avatar-1560447202.jpg",
          "type": "instructor"
        },
        {
          "id": 31880,
          "name": "Jared Falk",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg",
          "type": "instructor"
        },
        {
          "id": 239928,
          "name": "Stephane Chamberland",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/239928-avatar-1575288472.jpg",
          "type": "instructor"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg",
          "type": "instructor"
        }
      ],
      "showSkillLevel": true
    }
  }
}
```
