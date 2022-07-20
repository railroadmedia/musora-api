## Homepage Banner Data
The endpoint is available only on v1. The brand should be specified as a parameter.
It returns a JSON response with data for method, featured coach, songs, and coaches.

Each section should have: title, name, thumbnail_url, URL and link text


<a href="https://red-shadow-611407.postman.co/workspace/Team-Workspace~38bb093f-0978-4a83-8423-944a3c78fd51/example/9725390-bbdd45aa-0c26-43c6-89e9-34569e875d41"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/v1/homepage-banner?brand={brand}`

### Permissions
    - Only authenticated user can access the endpoint

### Request Example:

```js
$.ajax({
    url: 'https://app-staging-one.musora.com/' +
        '/musora-api/v1/homepage-banner?brand=guitareo',
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
  "method": {
    "title": "Step by Step Curriculum",
    "name": "Guitareo Method",
    "thumbnail_url": "https://musora-web-platform.s3.amazonaws.com/carousel/guitareo-method+1.jpg",
    "url": "https://devplatform.musora.com:8443/musora-api/v1/content/333654?brand=guitareo",
    "link": "Start Method",
    "level_rank": "1.1",
    "started": false,
    "completed": false,
    "user_progress": {
      "149628": []
    }
  },
  "featured_coach": {
    "title": "Featured Coach",
    "name": "Dean Lamb",
    "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/dean horizontal-1650893231.png",
    "url": "https://devplatform.musora.com:8443/musora-api/v1/content/354026?brand=guitareo",
    "link": "Visit Coach Page",
    "id": 354026
  },
  "songs": {
    "title": "Popular Songs in All Genres",
    "name": "Songs",
    "thumbnail_url": "https://musora-web-platform.s3.amazonaws.com/carousel/songs.jpg",
    "url": "https://devplatform.musora.com:8443/musora-api/v1/all?included_types%5B0%5D=song&brand=guitareo&page=1&limit=10&statuses%5B0%5D=published&sort=-published_on",
    "link": "See the latest songs"
  },
  "coaches": {
    "title": "Learn from the legends",
    "name": "Coaches",
    "thumbnail_url": "https://musora-web-platform.s3.amazonaws.com/carousel/coaches.jpg",
    "url": "https://devplatform.musora.com:8443/musora-api/v1/all?included_types%5B0%5D=instructor&required_fields%5B0%5D=is_coach%2C1&brand=guitareo&page=1&limit=10&statuses%5B0%5D=published&sort=-published_on",
    "link": "See Coaches"
  }
}
```
