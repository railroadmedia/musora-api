## Subscribe authenticated user to coach

Subscribe the authenticated user to selected content.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-a038be29-1b0f-4ea4-a632-1b9c949f83ca"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`PUT musora-api/follow`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key                |  required |  description           |
|------------------|---------------------|-----------|------------------------|
| query            |  content_id  |  yes      |  The authenticated user will be subscribed to selected content id.


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/follow',
    type: 'put',
    dataType: 'json',
    data:{
        content_id:281902,
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
  "id": 4,
  "content_id": 281902,
  "user_id": 149628,
  "created_on": "2021-11-11 13:39:03"
}
```