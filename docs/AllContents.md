## Filter contents
Get an array with contents data that respect filters criteria.
The results are paginated and respect the response structure defined in musora-api config file.

[<img align="right" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSUxC0ISgJe2YAO7vrTEam7j1wIur2KdsYjdg&usqp=CAU" width=100>](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-06a04692-d63f-4678-a0a6-293b5ae8df5d)

### HTTP Request
`GET musora-api/all`


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
        'included_types[]=course' + '&' +
        'statuses[]=published' + '&' +
        'filter[required_fields][]=topic,rock,string' + '&' +
        'filter[included_fields][]=topic,jazz,string' + '&' +
        'filter[included_fields][]=difficulty,3,integer' + '&' +
        'filter[included_fields][]=difficulty,9' + '&' +
        'filter[required_user_states][]=completed' + '&' +
        'filter[included_user_states][]=started',
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
      "id": 285555,
      "thumbnail_url": null,
      "type": "play-along",
      "published_on": "2021/03/13 16:00:00",
      "status": "published",
      "title": "Broadway Hustle",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Rob Brown"
      ],
      "artist": "Rob Brown",
      "style": "Pop/Rock",
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    },
    {
      "id": 285554,
      "thumbnail_url": null,
      "type": "performances",
      "published_on": "2021/03/13 16:00:00",
      "status": "published",
      "title": "Rob Brown - \"Broadway Hustle\"",
      "completed": false,
      "started": false,
      "progress_percent": 0,
      "is_added_to_primary_playlist": false,
      "instructors": [
        "Rob Brown"
      ],
      "artist": null,
      "style": null,
      "length_in_seconds": null,
      "parent_id": null,
      "name": null,
      "head_shot_picture_url": null
    }
  ],
  "meta": {
    "totalResults": 5688,
    "page": "1",
    "limit": "2",
    "filterOptions": {
      "content_type": [
        "25-days-of-christmas",
        "behind-the-scenes",
        "boot-camps",
        "camp-drumeo-ah",
        "challenges",
        "coach-stream",
        "course",
        "diy-drum-experiments",
        "exploring-beats",
        "gear-guides",
        "in-rhythm",
        "learning-path",
        "live",
        "namm-2019",
        "on-the-road",
        "pack",
        "paiste-cymbals",
        "performances",
        "play-along",
        "podcasts",
        "question-and-answer",
        "quick-tips",
        "rhythmic-adventures-of-captain-carson",
        "rhythms-from-another-planet",
        "semester-pack",
        "solos",
        "song",
        "sonor-drums",
        "student-collaborations",
        "student-focus",
        "study-the-greats",
        "tama-drums"
      ],
      "topic": [
        "All",
        "Alternative",
        "Beats",
        "Diddle Rudiments",
        "Ear Training",
        "Edutainment",
        "Fills",
        "Funk",
        "Gear",
        "Gear Talk",
        "Hand Technique",
        "Independence",
        "Jazz",
        "Latin",
        "Leader",
        "Metal",
        "Musicality",
        "Odd Time",
        "Performances",
        "Podcasts",
        "Question & Answer",
        "Rudiments",
        "Solos",
        "Styles",
        "Technique",
        "Theory"
      ],
      "style": [
        "All",
        "-",
        "Alternative",
        "Beats",
        "Blues",
        "Bues",
        "CCM/Worship",
        "Country",
        "Electronic",
        "Funk",
        "Funk. Electronic",
        "Funk. Odd Time",
        "Hip-Hop/Rap",
        "Jazz",
        "Latin",
        "Metal",
        "Odd Time",
        "Odd-Time",
        "Pop/Rock",
        "Pop/Rock,Blues",
        "Pop/Rock/Metal",
        "R&B",
        "R&B Electronic",
        "R&B/Soul",
        "Rock/Pop",
        "Swing",
        "World"
      ],
      "difficulty": [
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
      "instructor": [
        {
          "id": 31888,
          "name": "Aaron Edgar",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/aaron-edgar.png?v=1513185407",
          "type": "instructor"
        },
        {
          "id": 236679,
          "name": "Aaron Gillespie",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/236679-avatar-1573484038.jpg",
          "type": "instructor"
        },
        {
          "id": 267122,
          "name": "Yoni Madar",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/267122-avatar-1598445518.jpg",
          "type": "instructor"
        }
      ],
      "showSkillLevel": true
    }
  }
}
```
