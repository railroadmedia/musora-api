## Get authenticated user profile

Return information about the currently authenticated user: profile, experience and membership data.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-bf57000d-790a-4728-b52d-cfb400dae7df"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request

`GET musora-api/profile`

### Permissions

    - Only authenticated user

### Request Example:

```js

$.ajax({
    url: 'https://www.domain.com' +
        '/musora-api/profile',
    type: 'get',
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
  "id": 424855,
  "wordpressId": null,
  "ipbId": null,
  "email": "postman_test_user@drumeo.com",
  "permission_level": null,
  "login_username": "postman_test_user@drumeo.com",
  "display_name": "postman_test_user99078",
  "first_name": null,
  "last_name": null,
  "gender": null,
  "country": null,
  "region": null,
  "city": null,
  "birthday": "",
  "phone_number": null,
  "bio": null,
  "created_at": "2021-03-17 15:29:59",
  "updated_at": "2021-03-17 15:29:59",
  "avatarUrl": "https://s3.amazonaws.com/pianote/defaults/avatar.png",
  "totalXp": "0",
  "xpRank": "Enthusiast I",
  "isEdge": true,
  "isEdgeExpired": false,
  "edgeExpirationDate": null,
  "isPackOlyOwner": true,
  "isAppleAppSubscriber": false,
  "isGoogleAppSubscriber": false,
  "branches": {
    "experimentA": "X",
    "experimentB": "B-B"
  },
  "features": [
    "featureA",
    "featureB"
  ]
}
```

