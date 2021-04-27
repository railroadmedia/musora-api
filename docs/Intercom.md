## Create Intercom user
Create a new user in Intercom, with user's email address and tag `drumeo_started_app_signup_flow`


### HTTP Request
`PUT musora-api/intercom-user`


### Permissions
    - Without restrictions

### Request Parameters


|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|email|  yes  |User's email|


### Request Example:

```js
$.ajax({
    url: 'https://www.domain.com' +
             '/musora-api/intercom-user',
{
    "email": "email@email.ro"
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
}
```

### Response Example (500):
When the server-side reports an error, it returns a JSON object in the following format:
```json
{
  "success": false,
  "message": "Intercom exception when create intercom user. Message:message text"
}
```

