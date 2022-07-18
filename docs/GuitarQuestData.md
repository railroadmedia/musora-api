## Guitar Quest Data
The endpoint is available only on v1. The brand should be specified as a parameter.
It returns a JSON response with Guitar Quest data like levels(id, thumb_url, completed), total completed challenges and total completed lessons.



<a href="https://red-shadow-611407.postman.co/workspace/Team-Workspace~38bb093f-0978-4a83-8423-944a3c78fd51/example/9725390-c63ef13c-0cd1-4b92-8500-268de19e9fd9"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/v1/guitar-quest-map?brand={brand}`

### Permissions
    - Only authenticated user can access the endpoint

### Request Example:

```js
$.ajax({
    url: 'https://app-staging-one.musora.com/' +
        '/musora-api/v1/guitar-quest-map?brand=guitareo',
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
  "levels": [
    {
      "id": 280600,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-1.png",
      "completed": false
    },
    {
      "id": 280624,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-2.png",
      "completed": false
    },
    {
      "id": 280646,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-3.png",
      "completed": false
    },
    {
      "id": 280679,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-4.png",
      "completed": false
    },
    {
      "id": 280704,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-5.png",
      "completed": false
    },
    {
      "id": 280733,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-6.png",
      "completed": false
    },
    {
      "id": 280753,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-7.png",
      "completed": false
    },
    {
      "id": 280777,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-8.png",
      "completed": false
    },
    {
      "id": 280809,
      "thumb_url": "https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-9.png",
      "completed": false
    }
  ],
  "total_completed_challenges": 0,
  "total_completed_lessons": 0
}
```
