## Login
Registered users can login to establish their identity with the application using the API below.

The `token` value returned from the login must be used in the subsequent requests to musora-api in order to maintain user session.
The value uniquely identifies the user on the server and is used to enforce security policy, apply user and roles permissions and track usage analytics.
For all requests made after the login, the token value must be sent in the HTTP header.

If `purchases` array exists on the request, the in-app purchases are automatically restored.

### HTTP Request

`PUT musora-api/api/login`

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
{
    "email":   "email@email.ro",
    "password":    "password", 
    "purchases":
    [
        {
            "purchase_token": "aaaababab",
            "product_id": "pianote_app_1_month_member",
            "package_name": "com.pianote2"
        }
    ]
}
,
success: function (response) {
}
,
error: function (response) {
}
})
;
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

