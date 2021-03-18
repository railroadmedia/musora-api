## Upload user's avatar

This endpoint is used to upload avatar for user profile and returns the URL for the avatar image.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-6d58424e-d0cb-4013-bbc6-e34d24f02e21"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request

`POST musora-api/avatar/upload`

### Permissions

    - Only authenticated user

### Request Parameters

|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|file|  yes  | File upload|


### Request Example:

```js

$.ajax({
    url: 'https://www.domain.com' +
        '/musora-api/avatar/upload',
    type: 'POST',
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
      "url": "https://dzryyo1we6bm3.cloudfront.net/avatars/-1616052766-424855.jpg"
    }
  ]
}
```

