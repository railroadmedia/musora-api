
## Change password
The form where the user can enter the new password, using the link received after forgot password action.

### HTTP Request
`PUT musora-api/change-password`


### Permissions
    - Without restrictions

### Request Parameters


|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|rp-key|  yes  |Token provided in forgot password email|
|body|user_login|  yes  |User's email|
|body|pass1|  yes  |User's pass|


### Request Example:

```js
$.ajax({
    url: 'https://www.domain.com' +
             '/musora-api/change-password',
{
    "user_login": "email@email.ro",
    "rp_key": "accccc",
    "pass1": "new password"
}
   ,
    success: function(response) {},
    error: function(response) {}
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

### Response Example (500):
When the server-side reports an error, it returns a JSON object in the following format:
```json
{
  "success": false,
  "message": "Password reset failed, please try again."
}
```

