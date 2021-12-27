## All Coaches
Get an array with the coaches 
The results are paginated and respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/all?brand={brand}&limit={limit}&statuses[]={status}&included_types[]=coach&sort=-published_on&page={page}`


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
| query           |  only_subscribed                  |  no       |  false           |  Only subscribed will be returned.                                       |



### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/all?' +
        'page=1' + '&' +
        'limit=1' + '&' +
        'included_types[]=coach' + '&' +
        'only_subscribed=1',
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
      "id": 325903,
      "popularity": null,
      "thumbnail_url": null,
      "type": "coach",
      "published_on": "2021/11/08 08:20:52",
      "status": "published",
      "title": null,
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": "212",
      "parent_id": null,
      "name": "Aaron Gillespie",
      "head_shot_picture_url": null,
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/325903",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [
        "Beginner Focused",
        "Technique",
        "Touring"
      ],
      "genre": [
        "Odd Time",
        "Adult Contemporary",
        "Odd Time"
      ],
      "vimeo_video_id": "370407888",
      "youtube_video_id": null,
      "is_active": "0",
      "is_featured": "0",
      "focus_text": "Eius inventore debitis rerum animi omnis ut nihil. Dolorem distinctio quisquam minima error sit.",
      "short_bio": "Voluptate dolores dignissimos consequuntur.",
      "card_thumbnail_url": "https://drumeo-assets.s3.amazonaws.com/sales/2021/sub-pages/dorothea-taylor.png",
      "banner_background_image_url": "https://drumeo-assets.s3.amazonaws.com/sales/2021/sub-pages/kaz-rodriguez.png",
      "bio_image": "https://drumeo-assets.s3.amazonaws.com/sales/2021/sub-pages/john-wooton.png",
      "long_bio": "Provident atque vel ducimus et et eum.",
      "forum_thread_url": "Vel voluptate libero sit optio consectetur. Quam at aut qui minima id numquam aut.",
      "current_user_is_subscribed": true
    },
    {
      "id": 281911,
      "popularity": null,
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/281911-card-thumbnail-1624382247.png",
      "type": "coach",
      "published_on": "2021/11/08 08:20:51",
      "status": "published",
      "title": "Matt McGuire",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "artist": null,
      "style": null,
      "length_in_seconds": "4189",
      "parent_id": null,
      "name": "Matt McGuire",
      "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/281911-avatar-1609277722.png",
      "mobile_app_url": "https://dev.drumeo.com/laravel/public/api/content/281911",
      "live_event_start_time": null,
      "live_event_end_time": null,
      "focus": [
        "Rudiments",
        "Musicianship",
        "Technique"
      ],
      "genre": [
        "Odd Time",
        "Adult Contemporary",
        "Odd Time"
      ],
      "vimeo_video_id": "371437641",
      "youtube_video_id": null,
      "is_active": "1",
      "is_featured": "1",
      "focus_text": "Quasi dolore pariatur adipisci minima. Accusamus voluptatem non et vero.",
      "short_bio": "Error occaecati sit non voluptate. Quidem officiis sint aut ab.",
      "card_thumbnail_url": "https://drumeo-assets.s3.amazonaws.com/sales/2021/sub-pages/jared-falk.png",
      "banner_background_image_url": "https://drumeo-assets.s3.amazonaws.com/sales/2021/sub-pages/larnell-lewis.png",
      "bio_image": "https://drumeo-assets.s3.amazonaws.com/sales/2021/sub-pages/sarah-thawer.png",
      "long_bio": "Fuga sint qui et. Ut consequatur vitae ut quibusdam commodi voluptatem qui.",
      "forum_thread_url": "Excepturi omnis aut molestiae non.",
      "current_user_is_subscribed": true
    }
  ],
  "meta": {
    "totalResults": 2,
    "page": 1,
    "limit": 10,
    "filterOptions": {
      "content_type": [
        "coach"
      ],
      "genre": [
        "All",
        "Adult Contemporary",
        "Ballad",
        "Beats",
        "CCM/Worship",
        "Classical",
        "Folk",
        "Funk",
        "Fusion",
        "Latin",
        "Odd Time",
        "Pop",
        "Rock",
        "Soul"
      ],
      "focus": [
        "All",
        "Beginner Focused",
        "Composition",
        "Creativity",
        "Electronic Drums",
        "Groove",
        "Motivation",
        "Musicianship",
        "Performance",
        "Rudiments",
        "Technique",
        "Touring"
      ],
      "showSkillLevel": true
    }
  }
}
```
