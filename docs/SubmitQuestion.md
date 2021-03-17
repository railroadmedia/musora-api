## Submit user's question 

Send an email with user's question

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-9571030b-36c1-41f8-b97f-aaa8a33e7759"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>


### HTTP Request
`POST musora-api/submit-question`


### Permissions
    - Only authenticated user can access the endpoint

### Request Parameters

| path\|query\|body|  key      |  required |  description           |
|------------------|---------------------|-----------|--------------|
| body            |  question  |  yes  |    Question text

### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/submit-question',
    type: 'post',
    dataType: 'json',
    data:{
        "question":"Lorem ipsum"
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
  "message": "Your question has been submitted successfully and will be scheduled into one of our weekly Question and Answer sections! You will receive an email so you can check it out live, or here in the archives when you have time."
}
```