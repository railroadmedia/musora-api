## Login
Registered users can login to establish their identity with the application using the API below.

The `token` value returned from the login must be used in the subsequent requests to musora-api in order to maintain user session.
The value uniquely identifies the user on the server and is used to enforce security policy, apply user and roles permissions and track usage analytics.
For all requests made after the login, the token value must be sent in the HTTP header.

If `purchases` array exists on the request, the in-app purchases are automatically restored.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-d65a2579-0ddd-42ef-a5c0-b3169f5c8f58"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request

`PUT musora-api/login`

### Permissions

    - Without restrictions

### Request Parameters

|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|email|  yes  ||
|body|password|  yes  ||
|body|purchases|  no  |If purchases exist on the request, the in-app purchases are syncronized|

### Request Example:

```js

$.ajax({
    url: 'https://www.domain.com' +
        '/musora-api/login',
    type: 'put',
    dataType: 'json',
    data:{
        "email":   "email@email.ro",
        "password":    "password", 
        "purchases":
        [
            {
                "purchase_token": "aaaababab",
                "product_id": "pianote_app_1_month_member",
                "package_name": "com.pianote2",
            }
        ]
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
  "token": "eyJ0eX0YyJ9.ayJrvjNMrfDg78Aedglp6sEEoz6jzMLbHl7Gcy6Cygg",
  "isEdge": true,
  "isEdgeExpired": false,
  "edgeExpirationDate": null,
  "isPackOlyOwner": false,
  "tokenType": "bearer",
  "expiresIn": 3600,
  "userId": 1
}
```

### Response Example (401):
When the server-side reports an error, it returns a JSON object in the following format:
```json
{
  "success": false,
  "message": "Invalid Email or Password"
}
```

