## Update profile data for authenticated user 

Update use profile and return information about the currently authenticated user: profile, experience and membership data.
Firebase tokens (iOS and Android) are used by push notifications.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-5e473ba5-f991-4924-bb1f-ba9f62b9c83b"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request

`POST musora-api/profile/update`

### Permissions

    - Only authenticated user

|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|file|  no  | Avatar URL|
|body|phone_number|  no  | User's phone number|
|body|display_name|  no  | User's display name|
|body|firebase_token_ios|  no  | iOS Firebase token|
|body|firebase_token_android|  no  | Android Firebase token|


### Request Example:

```js

$.ajax({
    url: 'https://www.domain.com' +
        '/musora-api/profile/update',
    type: 'post',
    dataType: 'json',
    data:{
        "display_name":"Test user"
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
  "id": 424855,
  "wordpressId": null,
  "ipbId": null,
  "email": "postman_test_user@drumeo.com",
  "permission_level": null,
  "login_username": "postman_test_user@drumeo.com",
  "display_name": "Test user",
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
  "isGoogleAppSubscriber": false
}
```

