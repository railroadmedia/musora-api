## Unsubscribe authenticated user from coach

Unsubscribe the authenticated user from selected content.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-a038be29-1b0f-4ea4-a632-1b9c949f83ca"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`PUT musora-api/unfollow`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key                |  required |  description           |
|------------------|---------------------|-----------|------------------------|
| query            |  content_id  |  yes      |  The authenticated user will be unsubscribed from selected content id.


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/unfollow',
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

### Response Example (204):
```json
{}
```