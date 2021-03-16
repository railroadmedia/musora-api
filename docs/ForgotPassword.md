## Forgot password
An email is sent with a link to a webpage which contains a form where the user can enter the new password.

### HTTP Request
`PUT musora-api/forgot`


### Permissions
    - Without restrictions

### Request Parameters


|Type|Key|Required|Notes|
|----|---|--------|-----|
|body|email|  yes  ||


### Request Example:

```js
$.ajax({
    url: 'https://www.domain.com' +
             '/musora-api/forgot',
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
    "title": "Please check your email",
    "message": "Follow the instructions sent to your email address to reset your password."
}
```