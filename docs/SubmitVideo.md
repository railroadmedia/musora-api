## Submit video

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-73ea4dc8-9b86-4e52-b809-2e74d1666237"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`POST musora-api/submit-video`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key      |  required |  description           |
|------------------|---------------------|-----------|--------------|
| body            |  video  |  yes  |    Video url

### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/submit-video',
    type: 'post',
    dataType: 'json',
    data:{
        "video":"http://youdstube.com"
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
  "success": true,
  "title": "Thanks for your submission!",
  "message": "Our team will combine your video with the other student videos to create next months episode. Collaborations are typically released on the first of each month."
}
```