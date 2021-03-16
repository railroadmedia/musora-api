## Packs
Return an array with:
- top header pack: the pack that should be displayed into the top header section
- packs owned by authenticated user
- packs not owned by authenticated user, that can be purchased

The results respect the response structure defined in musora-api config file.

### HTTP Request
`GET musora-api/packs`

### Permissions
    - Only authenticated user can access the endpoint

[Try in Postman](https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-f8b68eae-7e02-4b7e-a99a-13a3eb27a263)


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/packs',
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
  "myPacks": [
    {
      "id": 234577,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/234577",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/234577-header-image-1603126132.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/independence-made-easy.svg"
    },
    {
      "id": 25814,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25814",
      "bundle_count": 15,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25814-header-image-1583255520.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/cobus-method.png"
    },
    {
      "id": 30223,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/30223",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/30223-header-image-1603126164.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/30223-logo-image-1586815773.png"
    },
    {
      "id": 25808,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25808",
      "bundle_count": 2,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25808-header-image-1583255847.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drumeo-masterclass.svg"
    },
    {
      "id": 249140,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/249140",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/249140-header-image-1603125909.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/RDM-logo.svg"
    },
    {
      "id": 227748,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/227748",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/227748-header-image-1603125977.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/227748-logo-image-1586815463.png"
    },
    {
      "id": 268039,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/268039",
      "bundle_count": 15,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/268039-header-image-1600280343.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/268039-logo-image-1600109873.png"
    },
    {
      "id": 248276,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/248276",
      "bundle_count": 1,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/248276-header-image-1585929686.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/248276-logo-image-1585262089.png"
    },
    {
      "id": 257242,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/257242",
      "bundle_count": 4,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/257242-header-image-1591718518.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/257242-logo-image-1590426146.png"
    },
    {
      "id": 265354,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/265354",
      "bundle_count": 1,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/265354-header-image-1596813200.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/265354-logo-image-1596813157.png"
    },
    {
      "id": 248762,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/248762",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/248762-header-image-1603126103.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drum-technique-made-easy.svg"
    },
    {
      "id": 223166,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/223166",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/223166-header-image-1603126145.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/223166-logo-image-1586815400.png"
    },
    {
      "id": 217074,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/217074",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/217074-header-image-1603126035.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/217074-logo-image-1586815488.png"
    },
    {
      "id": 213652,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/213652",
      "bundle_count": 4,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/213652-header-image-1583255638.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/hands-grooves-fills.png"
    },
    {
      "id": 213580,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/213580",
      "bundle_count": 3,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/213580-header-image-1583255646.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/in-constant-motion.png"
    },
    {
      "id": 213463,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/213463",
      "bundle_count": 3,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/213463-header-image-1583255653.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/beyond-the-chops.png"
    },
    {
      "id": 213341,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/213341",
      "bundle_count": 5,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/213341-header-image-1583255665.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/language-of-drumming.png"
    },
    {
      "id": 213297,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/213297",
      "bundle_count": 1,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/213297-header-image-1583255672.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/the-grid.png"
    },
    {
      "id": 213165,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/213165",
      "bundle_count": 5,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/213165-header-image-1583957326.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/great-hands-for-a-lifetime.png"
    },
    {
      "id": 213054,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/213054",
      "bundle_count": 3,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/213054-header-image-1583255685.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/anatomy-of-a-drum-solo.png"
    },
    {
      "id": 212924,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/212924",
      "bundle_count": 3,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/212924-header-image-1583255694.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/methods-mechanics.png"
    },
    {
      "id": 212899,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/212899",
      "bundle_count": 1,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/212899-header-image-1583255703.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/creative-control.png"
    },
    {
      "id": 208663,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/208663",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/208663-header-image-1603126151.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/208663-logo-image-1586815794.png"
    },
    {
      "id": 206302,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/206302",
      "bundle_count": 11,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/206302-header-image-1583864721.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/206302-logo-image-1583864727.svg"
    },
    {
      "id": 190255,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/190255",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/190255-header-image-1603126157.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/190255-logo-image-1586815355.png"
    },
    {
      "id": 29663,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/29663",
      "bundle_count": 3,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/29663-header-image-1583255714.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drumeo-for-teachers.svg"
    },
    {
      "id": 28436,
      "type": "semester-pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/28436",
      "bundle_count": false,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/28436-header-image-1603126170.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/28436-logo-image-1586815753.png"
    },
    {
      "id": 26555,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/26555",
      "bundle_count": 20,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/drumming-system.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drumming-system-1.svg"
    },
    {
      "id": 25822,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25822",
      "bundle_count": 1,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25822-header-image-1583255734.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drum-gear-buyers-guide.svg"
    },
    {
      "id": 25821,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25821",
      "bundle_count": 4,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/rock-drumming-system.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/rock-drumming-system.svg"
    },
    {
      "id": 25820,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25820",
      "bundle_count": 2,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25820-header-image-1583255747.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/moeller-method-secrets.svg"
    },
    {
      "id": 25819,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25819",
      "bundle_count": 2,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25819-header-image-1583255754.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drum-tuning-system.svg"
    },
    {
      "id": 25818,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25818",
      "bundle_count": 6,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/drum-play-along-system.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drum-play-along-system.svg"
    },
    {
      "id": 25817,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25817",
      "bundle_count": 3,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25817-header-image-1583255765.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/latin-drumming-system.svg"
    },
    {
      "id": 25816,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25816",
      "bundle_count": 3,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25816-header-image-1583255771.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/jazz-drumming-system.svg"
    },
    {
      "id": 25815,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25815",
      "bundle_count": 9,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25815-header-image-1583255790.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drum-fill-system.svg"
    },
    {
      "id": 25813,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25813",
      "bundle_count": 6,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25813-header-image-1583255802.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drum-rudiment-system.svg"
    },
    {
      "id": 25812,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25812",
      "bundle_count": 20,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25812-header-image-1583255808.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/bass-drum-secrets.svg"
    },
    {
      "id": 25811,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25811",
      "bundle_count": 5,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25811-header-image-1583255815.png",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/maximum-meytal.svg"
    },
    {
      "id": 25810,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25810",
      "bundle_count": 20,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25810-header-image-1603125739.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/drumming-system-2.svg"
    },
    {
      "id": 25809,
      "type": "pack",
      "is_new": false,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/25809",
      "bundle_count": 11,
      "thumbnail": "https://d1923uyy6spedc.cloudfront.net/25809-header-image-1603125639.jpg",
      "pack_logo": "https://d1923uyy6spedc.cloudfront.net/successful-drumming.svg"
    }
  ],
  "morePacks": [],
  "topHeaderPack": {
    "id": 234577,
    "type": "semester-pack",
    "started": true,
    "completed": false,
    "is_new": false,
    "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/pack/234577",
    "next_lesson_url": "https://staging.drumeo.com/laravel/public/musora-api/packs/jump-to-next-lesson/234577",
    "bundle_count": false,
    "thumbnail": "https://d1923uyy6spedc.cloudfront.net/234577-header-image-1603126132.jpg",
    "pack_logo": "https://d1923uyy6spedc.cloudfront.net/independence-made-easy.svg",
    "full_price": 0,
    "price": 0,
    "is_owned": true
  }
}
```
