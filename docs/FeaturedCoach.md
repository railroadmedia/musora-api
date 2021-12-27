## Featured Coaches
Get an array with the featured coaches 
The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/all?brand={brand}&limit={limit}&statuses[]={status}&required_fields[]=is_featured,1&included_types[]=coach&sort=-published_on&page={page}`


### Permissions
    - Only authenticated user can access the endpoint


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
| query           |  filter[required_user_states]     |  no       |  []              |  All returned contents are required to have these states for the authenticated user. Value format is: state                                                                                                                                                                     |
| query           |  filter[included_user_states]     |  no       |  []              |  Contents that have any of these states for the authenticated user will be returned. The first included user state is the same as a required user state but all included states after the first act inclusively. Value format is: state.                                        |



### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/all?' +
        'page=1' + '&' +
        'limit=1' + '&' +
        'included_types[]=coach' + '&' +
        'required_fields[]=is_featured,1',
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
      "id": 281911,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281911-card-thumbnail-1624382247.png",
      "type": "coach",
      "published_on": "2020/12/29 19:31:50",
      "status": "published",
      "title": "Matt McGuire",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": "Matt McGuire",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281911-avatar-1609277722.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281911",
      "live_event_start_time": null,
      "live_event_end_time": null
    }
  ],
  "meta": {
    "totalResults": 1,
    "page": "1",
    "limit": "10",
    "filterOptions": {
      "showSkillLevel": true
    }
  }
}
```
