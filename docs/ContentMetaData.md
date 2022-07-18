## Routines trailer
The endpoint is available only starting with version 1 . The brand should be specified as a parameter.
It returns a JSON response with meta data for specified content type.

<a href="https://red-shadow-611407.postman.co/workspace/Team-Workspace~38bb093f-0978-4a83-8423-944a3c78fd51/example/9725390-f2ed77a3-2cd7-41a4-a107-da5d79826950"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/musora-api/v1/content-meta?brand={brand}&content_type={contentType}`

### Permissions
    - Only authenticated user can access the endpoint

### Request Example:

```js
$.ajax({
    url: 'https://app-staging-one.musora.com/' +
        '/musora-api/v1/content-meta',
    type: 'get',
    dataType: 'json',
    data:{
        "brand":"pianote",
        "content_type":"bootcamps"
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
  "thumbnailUrl": "https://dpwjbsxqtam5n.cloudfront.net/shows/pianote/bootcamps.jpg",
  "name": "Bootcamps",
  "allowableFilters": [
    "instructor"
  ],
  "sortBy": "-published_on",
  "shortname": "Bootcamps",
  "icon": "icon-chords-scales-icon",
  "description": "Ready to work? Bootcamps are designed to help you really integrate and develop new skills. Select a topic that you want to improve on and get ready to play along with the lesson.",
  "amountOfFutureLessonsToShow": 3,
  "showFutureLessonAtTopOrBottom": "bottom",
  "episodeNumber": ""
}
```
