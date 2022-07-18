## Routines trailer
The endpoint is available only for v1 endpoints. The brand should be specified as a parameter.
It returns a JSON response with Routines trailer's data array.

<a href="https://red-shadow-611407.postman.co/workspace/Team-Workspace~38bb093f-0978-4a83-8423-944a3c78fd51/example/9725390-917ebfbd-f263-49a0-806b-36a7d536a767"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/v1/routine-trailer?brand={brand}`

### Permissions
    - Only authenticated user can access the endpoint

### Request Example:

```js
$.ajax({
    url: 'https://app-staging-one.musora.com/' +
        '/musora-api/v1/routine-trailer?brand={brand}',
    type: 'get',
    dataType: 'json',
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
  "vimeo_video_id": "578243377",
  "video_playback_endpoints": [
    {
      "file": "https://player.vimeo.com/progressive_redirect/playback/578243377/rendition/540p/file.mp4?loc=external&oauth2_token_id=1284792283&signature=f8a01157cd5707e6ade5f84a867edbd4f549f091c6c284c5352ec290b9db7bf7",
      "width": 960,
      "height": 540
    },
    {
      "file": "https://player.vimeo.com/progressive_redirect/playback/578243377/rendition/360p/file.mp4?loc=external&oauth2_token_id=1284792283&signature=9eff053636a9b8abc19a0e44ae437f7fa50a0cf0a83c39373e8ae29b50dff9b6",
      "width": 640,
      "height": 360
    },
    {
      "file": "https://player.vimeo.com/progressive_redirect/playback/578243377/rendition/1440p/file.mp4?loc=external&oauth2_token_id=1284792283&signature=c0309249419ce7f80d9bbc89c63851fbb194e623fca6046b7e685611627eceac",
      "width": 2560,
      "height": 1440
    },
    {
      "file": "https://player.vimeo.com/progressive_redirect/playback/578243377/rendition/1080p/file.mp4?loc=external&oauth2_token_id=1284792283&signature=a3b326c9c79746ddf4915133220f70e4f9498e5fb089ebba0361583d2a55376d",
      "width": 1920,
      "height": 1080
    },
    {
      "file": "https://player.vimeo.com/progressive_redirect/playback/578243377/rendition/2160p/file.mp4?loc=external&oauth2_token_id=1284792283&signature=6ea971d1165f0f1df7af46ccdc33041a70e917028d1c1cd7af4279edd2ef9550",
      "width": 3840,
      "height": 2160
    },
    {
      "file": "https://player.vimeo.com/progressive_redirect/playback/578243377/rendition/720p/file.mp4?loc=external&oauth2_token_id=1284792283&signature=8ccd1473aae48a510d44d38df2312a72c254e210e230f79cc0c3efae7b82f6b6",
      "width": 1280,
      "height": 720
    }
  ],
  "length_in_seconds": 82
}
```
