## Submit student focus

Submit student focus

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-058d22b0-b23c-4f15-bb83-45e7e2f60068"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>


### HTTP Request
`POST musora-api/submit-student-focus-form`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key      |  required |  description           |
|------------------|---------------------|-----------|--------------|
| body            |  experience  |  yes  |   
| body            |  improvement  |  yes  |    
| body            |  weakness  |  yes  |    
| body            |  instructor_focus  |  yes  |    
| body            |  goal  |  yes  |


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/submit-student-focus-form',
    type: 'post',
    dataType: 'json',
    data:{
        "experience":"Lorem ipsum",
        "improvement":"Lorem",
        "weakness":"Lorem ...",
        "instructor_focus":"cccc",
        "goal":"test"
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
  "message": "Your submission has been sent to a Drumeo Instructor. Typically, they'll email you within 48 hours to let you know when your review and custom Student Plan will be ready!"
}
```