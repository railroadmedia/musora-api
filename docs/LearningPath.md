## Learning path
Return learning path content based on the slug.
The results respect the response structure defined in musora-api config file.

<a href="https://www.postman.com/red-shadow-611407/workspace/staging-drumeo-with-musora-api/request/9725390-07fe0336-1462-4223-9601-f065ad0c6481"  target="_blank" style="float:right;">
<img width="120px" src="https://images.ctfassets.net/1wryd5vd9xez/1sHuHRROdF7ifCjy4QKVXk/a44e85c6138dbe13126c4ede8650cf29/https___cdn-images-1.medium.com_max_2000_1_O0OZO4m6nbwwnYAtkSQO0g.png"/>
</a>

### HTTP Request
`GET musora-api/learning-paths/{methodSlug}`

### Permissions
    - Only authenticated user can access the endpoint


### Request Example:

```js
$.ajax({
    url: 'https://www.musora.com' +
        '/musora-api/learning-paths/drumeo-method',
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
  "id": 241247,
  "started": true,
  "completed": false,
  "level_rank": "2.0",
  "title": "The Drumeo Method",
  "vimeo_video_id": "382231267",
  "description": "The Drumeo Method is a step-by-step curriculum designed to take students from a beginner to advanced level. Students will work with a wide range of instructors as they develop their skills in areas like theory, rudiments, technique, styles, and ear training.",
  "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241247-card-thumbnail-1577145625.jpg",
  "published_on": "2019/12/30 08:00:00",
  "levels": [
    {
      "id": 241248,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/getting-started-on-the-drums",
      "published_on": "2019/12/27 08:00:00",
      "description": "Level 1 is where you’ll develop a rock-solid foundation for your drumming that will prepare you for all the other levels in The Drumeo Method and set you up for success for the rest of your drumming journey. Whether you want to be a rock, jazz, gospel, or blues drummer - to name only a few – the skills you’ll learn in this level will help you get there. You’re going to learn how to set up your drum-set, how to properly hold your drumsticks and play your pedals, and best of all, you’ll learn how to play two songs on the drums in no time! You will also start to develop your ears in this level so you can simply play what you hear. ",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241248-card-thumbnail-1577144969.jpg",
      "position": 1,
      "progress_percent": 100,
      "title": "Getting Started On The Drums",
      "instructor": [
        {
          "id": 31880,
          "name": "Jared Falk",
          "biography": "<p>Jared Falk is a co-founder of Drumeo and author of the best-seller instructional programs &ldquo;Successful Drumming&rdquo; and &ldquo;Bass Drum Secrets&rdquo;. With over 15 years of experience teaching drummers from all over the world, Jared is known for his simplified teaching methods and high level of enthusiasm for the drumming community. He&rsquo;s a master of the heel-toe foot technique and a proficient rock/funk drummer, whose sole objective is making your experience behind a drum set, fulfilling and fun.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg"
        }
      ]
    },
    {
      "id": 241284,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/basic-theory-ear-training",
      "published_on": "2020/01/31 22:00:00",
      "description": "Theory and notation, rudiments, independence, new styles, and ear training! Level 2 is where you’ll start to develop skills in some core areas that you’ll continue to work on throughout the rest of the Drumeo Method. You’ll learn some basic rudiments which are considered the alphabet of drumming, you’ll learn how to read drum notation and understand basic theory, you’ll learn how to practice effectively, and you’ll even dive into drumming styles like rock, punk, and metal. Level 2 is also where you’ll start working on some new ear training skills to become a more musical drummer. These are invaluable skills that you’ll be able to apply to some new play-along tracks in this level and all of the other music you love to play!",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241284-card-thumbnail-1577144975.jpg",
      "position": 2,
      "progress_percent": 0,
      "title": "Basic Theory & Ear Training",
      "instructor": [
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "biography": "John Wooton is a world renowned marching percussion clinician. He's been associated with five P.A.S.I.C. Marching Percussion Forum champions. He's won several individual snare drum titles including the Percussive Arts Society National Championship and the Drum Corps Midwest Championship. John is currently the Director of Percussion Studies at The University of Southern Mississippi in Hattiesburg, Miss. He's released many books including \"Dr. Throwdown's Rudimental Remedies\", \"The Drummer's Rudimental Reference Book\", and others. John has performed clinics and recitals for Days of Percussion and marching percussion all over the United States, Canada, England, Argentina, Brazil, and Puerto Rico.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407"
        },
        {
          "id": 31878,
          "name": "Mike Michalkow",
          "biography": "Mike Michalkow has been teaching drums and percussion for over 20 years, having studied under master drummers Dom Famularo, Jim Chapin, Chuck Silverman, Thomas Lang, John “JR” Robinson, Peter Magadini, and Virgil Donati. Known as a versatile instructor, Mike’s simplified but comprehensive teaching methods have helped thousands of drummers around the world reach their goals, through bestsellers like the “Drumming System”, “Jazz Drumming System”, “Latin Drumming System”, “Moeller Method Secrets”, and “Total Rock Drummer”.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/mike-michalkow.png?v=1513185407"
        }
      ]
    },
    {
      "id": 241285,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/the-motions-of-drumming",
      "published_on": "2020/02/29 17:00:00",
      "description": "This level will change the way you approach playing the drums. By learning the motions of drumming, you will become a more efficient drummer able to play faster, smoother, and for longer periods of time. This is no easy task, but the payoff is huge! Taking the time to properly learn the motions will help you avoid injury and drastically improve the sound of your drumming. Level 3 will also introduce you to some new rudiments like the flam and drag, some new styles like country and disco, and expand on the theory, independence, and ear training concepts you learned about in Level 2. ",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241285-card-thumbnail-1577144979.jpg",
      "position": 3,
      "progress_percent": 0,
      "title": "The Motions Of Drumming",
      "instructor": [
        {
          "id": 31964,
          "name": "Bruce Becker",
          "biography": "Drummer, educator, and producer Bruce Becker is the founding member and drummer of the David Becker Tribune. Bruce spent a long period of time from 1977 to 1984 studying with Freddie Gruber, which has influenced the past three decades of his overall drum education approach and teaching methods. His reputation has caused a vast amount of drummers to seek his teachings such as David Garibaldi, Mark Schulman, Daniel Glass, Glen Sobel, and many more. Bruce's drumming has been heard across a wide variety of television such as Monday Night Football, Entertainment Tonight, The Weather Channel, and advertisements.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/1514589819-bruce.jpg"
        },
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "biography": "John Wooton is a world renowned marching percussion clinician. He's been associated with five P.A.S.I.C. Marching Percussion Forum champions. He's won several individual snare drum titles including the Percussive Arts Society National Championship and the Drum Corps Midwest Championship. John is currently the Director of Percussion Studies at The University of Southern Mississippi in Hattiesburg, Miss. He's released many books including \"Dr. Throwdown's Rudimental Remedies\", \"The Drummer's Rudimental Reference Book\", and others. John has performed clinics and recitals for Days of Percussion and marching percussion all over the United States, Canada, England, Argentina, Brazil, and Puerto Rico.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407"
        },
        {
          "id": 212107,
          "name": "Dom Famularo",
          "biography": "Dom Famularo is a drummer, author, and motivational speaker who's arguably the most influential educator in the drumming community. He's traveled the globe for the past 40 years holding masterclasses and clinics, and has been one of drumming's most sought-after private instructors from his fans around the world. Dom has been tutored by legendary greats, including Joe Morello, Jim Chapin, Al Miller, Charlie Perry, Colin Bailey, and Shelly Manne, among many others. He has recorded and/or performed with artists such as the Buddy Rich Big Band, B.B. King, Lionel Hampton, Chuck Leavell (Rolling Stones), and \"T\" Lavitz (The Dixie Dregs), and has shared the stage with other drumming giants such as Dave Weckl, Steve Gadd, Vinnie Colaiuta, Simon Phillips, Billy Cobham, Bernard Purdie, and Chad Smith.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dom-famularo.jpg"
        },
        {
          "id": 31900,
          "name": "Jim Riley",
          "biography": "Jim Riley is the drummer and bandleader for the multi-platinum country music group, Rascal Flatts. Since moving to Nashville in 1997, he has played over a 1000 sold out shows for millions of fans, in addition to television credits including the Grammy Awards, The Tonight Show, the American Music Awards, The Voice, American Idol, Dancing with the Stars, Oprah, and The Today Show. Renown as one of the best in the business, Jim has been voted “Best Country Drummer” and “Best Drum Clinician” by the readers of Modern Drummer magazine, and “Country Drummer of the Year” by the readers of DRUM! magazine.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/jim-riley.png?v=1513185407"
        }
      ]
    },
    {
      "id": 241286,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/the-moeller-method-essential-grooves",
      "published_on": "2020/05/31 16:00:00",
      "description": "Now that you’ve started to develop the motions of drumming, you’re ready to start learning the Moeller Method. This is a technique that drummers have been using for decades to achieve power, efficiency, and fluidity on the drums. Level 4 is where you’ll take your technical abilities to a new level, begin to play in different time signatures, and start exploring new rhythmic concepts like half-time feels and filling over the barline. You’ll also be introduced to jazz, blues, and rock ballads, in addition to learning how to develop musicality within each new style.",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241286-card-thumbnail-1577144984.jpg",
      "position": 4,
      "progress_percent": 0,
      "title": "The Moeller Method & Essential Grooves",
      "instructor": [
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "biography": "John Wooton is a world renowned marching percussion clinician. He's been associated with five P.A.S.I.C. Marching Percussion Forum champions. He's won several individual snare drum titles including the Percussive Arts Society National Championship and the Drum Corps Midwest Championship. John is currently the Director of Percussion Studies at The University of Southern Mississippi in Hattiesburg, Miss. He's released many books including \"Dr. Throwdown's Rudimental Remedies\", \"The Drummer's Rudimental Reference Book\", and others. John has performed clinics and recitals for Days of Percussion and marching percussion all over the United States, Canada, England, Argentina, Brazil, and Puerto Rico.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407"
        },
        {
          "id": 31964,
          "name": "Bruce Becker",
          "biography": "Drummer, educator, and producer Bruce Becker is the founding member and drummer of the David Becker Tribune. Bruce spent a long period of time from 1977 to 1984 studying with Freddie Gruber, which has influenced the past three decades of his overall drum education approach and teaching methods. His reputation has caused a vast amount of drummers to seek his teachings such as David Garibaldi, Mark Schulman, Daniel Glass, Glen Sobel, and many more. Bruce's drumming has been heard across a wide variety of television such as Monday Night Football, Entertainment Tonight, The Weather Channel, and advertisements.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/1514589819-bruce.jpg"
        },
        {
          "id": 31900,
          "name": "Jim Riley",
          "biography": "Jim Riley is the drummer and bandleader for the multi-platinum country music group, Rascal Flatts. Since moving to Nashville in 1997, he has played over a 1000 sold out shows for millions of fans, in addition to television credits including the Grammy Awards, The Tonight Show, the American Music Awards, The Voice, American Idol, Dancing with the Stars, Oprah, and The Today Show. Renown as one of the best in the business, Jim has been voted “Best Country Drummer” and “Best Drum Clinician” by the readers of Modern Drummer magazine, and “Country Drummer of the Year” by the readers of DRUM! magazine.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/jim-riley.png?v=1513185407"
        },
        {
          "id": 212107,
          "name": "Dom Famularo",
          "biography": "Dom Famularo is a drummer, author, and motivational speaker who's arguably the most influential educator in the drumming community. He's traveled the globe for the past 40 years holding masterclasses and clinics, and has been one of drumming's most sought-after private instructors from his fans around the world. Dom has been tutored by legendary greats, including Joe Morello, Jim Chapin, Al Miller, Charlie Perry, Colin Bailey, and Shelly Manne, among many others. He has recorded and/or performed with artists such as the Buddy Rich Big Band, B.B. King, Lionel Hampton, Chuck Leavell (Rolling Stones), and \"T\" Lavitz (The Dixie Dregs), and has shared the stage with other drumming giants such as Dave Weckl, Steve Gadd, Vinnie Colaiuta, Simon Phillips, Billy Cobham, Bernard Purdie, and Chad Smith.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dom-famularo.jpg"
        },
        {
          "id": 237359,
          "name": "Glen Sobel",
          "biography": "<p>Glen Sobel, an L.A. born and raised drummer, is a familiar name within the music industry. He has held the drum chair with Alice Cooper for over eight years and has been working with Hollywood Vampires, featuring Alice, Joe Perry, and Johnny Depp! Beyond these incredible gigs, Glen has also toured with Chris Impellitteri, Jennifer Batten (Michael Jackson's guitarist), Tony Macalpine, Gary Hoey, Warner Bros. recording act Beautiful Creatures (Ozzfest tour), and Cypress Hill, among many others.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/237359-avatar-1574076692.jpg"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "biography": null,
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg"
        }
      ]
    },
    {
      "id": 241287,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/rhythmic-groupings-improvisation",
      "published_on": "2020/11/02 20:00:00",
      "description": "Every song you’ve ever heard is made up of different groupings of notes. In the same way, this is true for all of the patterns that you play on the drums! Level 5 is where you’ll be introduced to the concept of groupings and how you can use them to create different grooves and fills. In this level, you’ll start to explore new styles like funk, Motown, reggae, and bossa nova, and continue to develop your 4-limb independence, understanding of theory and notation, and ability to create musical drum parts in different styles.",
      "thumbnail_url": null,
      "position": 5,
      "progress_percent": 0,
      "title": "Rhythmic Groupings & Independence",
      "instructor": [
        {
          "id": 31959,
          "name": "Brandon Toews",
          "biography": "<p>Brandon Toews is an author, educator, and performer based out of Vancouver, Canada. He is the author of The Drummer's Toolbox and the co-author of The Best Beginner Drum Book, which was written in collaboration with Drumeo co-founder Jared Falk. In addition to creating educational resources for drummers, he has acquired his Bachelor of Music degree in Jazz and Contemporary Popular Music from MacEwan University in Edmonton, Canada with a major in Music Performance. Having played drums for the past 15 years, Brandon has studied with many notable educators and performers including Jared Falk (Drumeo), Colin Stranahan (Jonathan Kreisberg; Kurt Rosenwinkel), and Brian Thurgood (Edmonton Symphony Orchestra; MacEwan University). Brandon is also the Product Director at Musora Media Inc., home to the award-winning online music education platforms Drumeo, Pianote, and Guitareo.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31959-avatar-1560447202.jpg"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "biography": "John Wooton is a world renowned marching percussion clinician. He's been associated with five P.A.S.I.C. Marching Percussion Forum champions. He's won several individual snare drum titles including the Percussive Arts Society National Championship and the Drum Corps Midwest Championship. John is currently the Director of Percussion Studies at The University of Southern Mississippi in Hattiesburg, Miss. He's released many books including \"Dr. Throwdown's Rudimental Remedies\", \"The Drummer's Rudimental Reference Book\", and others. John has performed clinics and recitals for Days of Percussion and marching percussion all over the United States, Canada, England, Argentina, Brazil, and Puerto Rico.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407"
        },
        {
          "id": 31964,
          "name": "Bruce Becker",
          "biography": "Drummer, educator, and producer Bruce Becker is the founding member and drummer of the David Becker Tribune. Bruce spent a long period of time from 1977 to 1984 studying with Freddie Gruber, which has influenced the past three decades of his overall drum education approach and teaching methods. His reputation has caused a vast amount of drummers to seek his teachings such as David Garibaldi, Mark Schulman, Daniel Glass, Glen Sobel, and many more. Bruce's drumming has been heard across a wide variety of television such as Monday Night Football, Entertainment Tonight, The Weather Channel, and advertisements.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/1514589819-bruce.jpg"
        },
        {
          "id": 237359,
          "name": "Glen Sobel",
          "biography": "<p>Glen Sobel, an L.A. born and raised drummer, is a familiar name within the music industry. He has held the drum chair with Alice Cooper for over eight years and has been working with Hollywood Vampires, featuring Alice, Joe Perry, and Johnny Depp! Beyond these incredible gigs, Glen has also toured with Chris Impellitteri, Jennifer Batten (Michael Jackson's guitarist), Tony Macalpine, Gary Hoey, Warner Bros. recording act Beautiful Creatures (Ozzfest tour), and Cypress Hill, among many others.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/237359-avatar-1574076692.jpg"
        },
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "biography": null,
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg"
        },
        {
          "id": 202287,
          "name": "Sarah Thawer",
          "biography": "Sarah Thawer has been making a name for herself since setting foot on stage to perform for the very first time, when she was only 6 years old. A self-taught drummer at heart, Sarah has found great success by embracing the world of formal education, by studying jazz and world music at York University where she was the recipient of the Oscar Peterson Scholarship, the highest award given by the institution, in addition to graduating with the Summa Cum Laude distinction. All her accolades and work ethic have taken her to share a stage with world-class artists including AR Rahman, Ruth B, Jane Bunnett and Maqueque, Del Hartley, and D’bi and the 333, just to name a few.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/sarah-thawer.jpg"
        },
        {
          "id": 31895,
          "name": "Larnell Lewis",
          "biography": "Larnell Lewis is a versatile and sought-after drummer, composer, producer, educator, and clinician, who has performed with Snarky Puppy, Fred Hammond, Jully Black, and Glen Lewis. A professor at Humber College's Faculty of Music, where as a student he received the Oscar Peterson Award for “Outstanding Achievement in Music”, Larnell is the ultimate groove master and one of the go-to Drumeo instructors for developing an awesome feel.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/larnell-lewis.png?v=1513185407"
        }
      ]
    },
    {
      "id": 241288,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/odd-time-finger-control",
      "published_on": "2020/12/01 20:00:00",
      "description": "Level 6 is where you’ll start to move into some more challenging material. You’ll learn how to play in odd time signatures, how to develop finger control, and how to play tons of new styles ranging from hip-hop to soca to the second line. In this level, you’ll also learn some new rudiments, create new beats and fills with odd note groupings, explore new independence concepts, and continue to develop your soloing skills.",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241288-card-thumbnail-1577144999.jpg",
      "position": 6,
      "progress_percent": 0,
      "title": "Odd Time & Improvisation",
      "instructor": [
        {
          "id": 31888,
          "name": "Aaron Edgar",
          "biography": "Aaron Edgar is a professional studio and touring musician with over 15 years of teaching experience. Coupling his educational know-how with the ability to play various styles of music with ease and taking rhythmic concepts to a whole new level, makes for yet another fantastic drummer to learn from and get inspired by on Drumeo. Aaron is our very own rhythm illusionist: a master at making odd concepts like polyrhythms and metric modulation, easy to understand and apply to the kit.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/aaron-edgar.png?v=1513185407"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "biography": "John Wooton is a world renowned marching percussion clinician. He's been associated with five P.A.S.I.C. Marching Percussion Forum champions. He's won several individual snare drum titles including the Percussive Arts Society National Championship and the Drum Corps Midwest Championship. John is currently the Director of Percussion Studies at The University of Southern Mississippi in Hattiesburg, Miss. He's released many books including \"Dr. Throwdown's Rudimental Remedies\", \"The Drummer's Rudimental Reference Book\", and others. John has performed clinics and recitals for Days of Percussion and marching percussion all over the United States, Canada, England, Argentina, Brazil, and Puerto Rico.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407"
        },
        {
          "id": 31880,
          "name": "Jared Falk",
          "biography": "<p>Jared Falk is a co-founder of Drumeo and author of the best-seller instructional programs &ldquo;Successful Drumming&rdquo; and &ldquo;Bass Drum Secrets&rdquo;. With over 15 years of experience teaching drummers from all over the world, Jared is known for his simplified teaching methods and high level of enthusiasm for the drumming community. He&rsquo;s a master of the heel-toe foot technique and a proficient rock/funk drummer, whose sole objective is making your experience behind a drum set, fulfilling and fun.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg"
        },
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 31964,
          "name": "Bruce Becker",
          "biography": "Drummer, educator, and producer Bruce Becker is the founding member and drummer of the David Becker Tribune. Bruce spent a long period of time from 1977 to 1984 studying with Freddie Gruber, which has influenced the past three decades of his overall drum education approach and teaching methods. His reputation has caused a vast amount of drummers to seek his teachings such as David Garibaldi, Mark Schulman, Daniel Glass, Glen Sobel, and many more. Bruce's drumming has been heard across a wide variety of television such as Monday Night Football, Entertainment Tonight, The Weather Channel, and advertisements.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/1514589819-bruce.jpg"
        },
        {
          "id": 237359,
          "name": "Glen Sobel",
          "biography": "<p>Glen Sobel, an L.A. born and raised drummer, is a familiar name within the music industry. He has held the drum chair with Alice Cooper for over eight years and has been working with Hollywood Vampires, featuring Alice, Joe Perry, and Johnny Depp! Beyond these incredible gigs, Glen has also toured with Chris Impellitteri, Jennifer Batten (Michael Jackson's guitarist), Tony Macalpine, Gary Hoey, Warner Bros. recording act Beautiful Creatures (Ozzfest tour), and Cypress Hill, among many others.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/237359-avatar-1574076692.jpg"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "biography": null,
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg"
        },
        {
          "id": 31922,
          "name": "Mark Kelso",
          "biography": "Mark Kelso is a talented musician, composer, and producer who is currently head of percussion at Humber College in Toronto. Mark's ability to play a wide variety of musical styles has scored him gigs with artists such as Michael Buble, Shania Twain, Gino Vanelli, and Pete Townsend. Mark has been featured in ​Drums ETC., ​​Canadian Musician, ​and ​DRUM!​ magazine and has also been a featured clinician for many different drum festivals such as Montreal Drumfest, Musicfest, and Percussive Arts Society International Convention (PASIC).",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/mark-kelso.png?v=1513185407"
        }
      ]
    },
    {
      "id": 241289,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/foot-technique-combinations",
      "published_on": "2020/12/31 18:00:00",
      "description": "This level focuses on developing your foot technique with both single and double pedals. That’s right… double pedals! You’ll learn about the slide, heel-toe, and swivel techniques, how to develop speed and endurance, and best of all, how to apply all of this to heavy metal and punk music. Level 7 is also where you’ll learn about hand-to-foot combinations and how to create your own drum fills using different combinations. Even if punk and metal aren’t your styles of choice, the concepts in this level are essential for any drummer wanting to develop their creativity and versatility behind the kit.",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241289-card-thumbnail-1577145007.jpg",
      "position": 7,
      "progress_percent": 0,
      "title": "Foot Technique & Combinations",
      "instructor": [
        {
          "id": 202287,
          "name": "Sarah Thawer",
          "biography": "Sarah Thawer has been making a name for herself since setting foot on stage to perform for the very first time, when she was only 6 years old. A self-taught drummer at heart, Sarah has found great success by embracing the world of formal education, by studying jazz and world music at York University where she was the recipient of the Oscar Peterson Scholarship, the highest award given by the institution, in addition to graduating with the Summa Cum Laude distinction. All her accolades and work ethic have taken her to share a stage with world-class artists including AR Rahman, Ruth B, Jane Bunnett and Maqueque, Del Hartley, and D’bi and the 333, just to name a few.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/sarah-thawer.jpg"
        },
        {
          "id": 31880,
          "name": "Jared Falk",
          "biography": "<p>Jared Falk is a co-founder of Drumeo and author of the best-seller instructional programs &ldquo;Successful Drumming&rdquo; and &ldquo;Bass Drum Secrets&rdquo;. With over 15 years of experience teaching drummers from all over the world, Jared is known for his simplified teaching methods and high level of enthusiasm for the drumming community. He&rsquo;s a master of the heel-toe foot technique and a proficient rock/funk drummer, whose sole objective is making your experience behind a drum set, fulfilling and fun.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31880-avatar-1557351774.jpg"
        },
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "biography": null,
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg"
        },
        {
          "id": 31895,
          "name": "Larnell Lewis",
          "biography": "Larnell Lewis is a versatile and sought-after drummer, composer, producer, educator, and clinician, who has performed with Snarky Puppy, Fred Hammond, Jully Black, and Glen Lewis. A professor at Humber College's Faculty of Music, where as a student he received the Oscar Peterson Award for “Outstanding Achievement in Music”, Larnell is the ultimate groove master and one of the go-to Drumeo instructors for developing an awesome feel.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/larnell-lewis.png?v=1513185407"
        }
      ]
    },
    {
      "id": 241290,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/brushes-texture-articulation",
      "published_on": "2021/02/01 20:00:00",
      "description": "Throughout the first seven levels, you learned a lot about what to play on the drums. In level 8, you’re going to focus on the how of drumming by exploring articulation and texture. Level 8 is also where you’ll learn how to play with brushes, explore styles from Cuba and Brazil like mozambique and samba, and learn to read and understand charts, lead sheets, and song forms used in jazz and Latin music. These are all essential skills, especially if you want to become a gigging drummer.",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241290-card-thumbnail-1577145012.jpg",
      "position": 8,
      "progress_percent": 0,
      "title": "Brushes, Texture & Articulation",
      "instructor": [
        {
          "id": 31959,
          "name": "Brandon Toews",
          "biography": "<p>Brandon Toews is an author, educator, and performer based out of Vancouver, Canada. He is the author of The Drummer's Toolbox and the co-author of The Best Beginner Drum Book, which was written in collaboration with Drumeo co-founder Jared Falk. In addition to creating educational resources for drummers, he has acquired his Bachelor of Music degree in Jazz and Contemporary Popular Music from MacEwan University in Edmonton, Canada with a major in Music Performance. Having played drums for the past 15 years, Brandon has studied with many notable educators and performers including Jared Falk (Drumeo), Colin Stranahan (Jonathan Kreisberg; Kurt Rosenwinkel), and Brian Thurgood (Edmonton Symphony Orchestra; MacEwan University). Brandon is also the Product Director at Musora Media Inc., home to the award-winning online music education platforms Drumeo, Pianote, and Guitareo.</p>",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/31959-avatar-1560447202.jpg"
        },
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "biography": null,
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "biography": "John Wooton is a world renowned marching percussion clinician. He's been associated with five P.A.S.I.C. Marching Percussion Forum champions. He's won several individual snare drum titles including the Percussive Arts Society National Championship and the Drum Corps Midwest Championship. John is currently the Director of Percussion Studies at The University of Southern Mississippi in Hattiesburg, Miss. He's released many books including \"Dr. Throwdown's Rudimental Remedies\", \"The Drummer's Rudimental Reference Book\", and others. John has performed clinics and recitals for Days of Percussion and marching percussion all over the United States, Canada, England, Argentina, Brazil, and Puerto Rico.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407"
        },
        {
          "id": 31964,
          "name": "Bruce Becker",
          "biography": "Drummer, educator, and producer Bruce Becker is the founding member and drummer of the David Becker Tribune. Bruce spent a long period of time from 1977 to 1984 studying with Freddie Gruber, which has influenced the past three decades of his overall drum education approach and teaching methods. His reputation has caused a vast amount of drummers to seek his teachings such as David Garibaldi, Mark Schulman, Daniel Glass, Glen Sobel, and many more. Bruce's drumming has been heard across a wide variety of television such as Monday Night Football, Entertainment Tonight, The Weather Channel, and advertisements.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/1514589819-bruce.jpg"
        },
        {
          "id": 31922,
          "name": "Mark Kelso",
          "biography": "Mark Kelso is a talented musician, composer, and producer who is currently head of percussion at Humber College in Toronto. Mark's ability to play a wide variety of musical styles has scored him gigs with artists such as Michael Buble, Shania Twain, Gino Vanelli, and Pete Townsend. Mark has been featured in ​Drums ETC., ​​Canadian Musician, ​and ​DRUM!​ magazine and has also been a featured clinician for many different drum festivals such as Montreal Drumfest, Musicfest, and Percussive Arts Society International Convention (PASIC).",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/mark-kelso.png?v=1513185407"
        },
        {
          "id": 31895,
          "name": "Larnell Lewis",
          "biography": "Larnell Lewis is a versatile and sought-after drummer, composer, producer, educator, and clinician, who has performed with Snarky Puppy, Fred Hammond, Jully Black, and Glen Lewis. A professor at Humber College's Faculty of Music, where as a student he received the Oscar Peterson Award for “Outstanding Achievement in Music”, Larnell is the ultimate groove master and one of the go-to Drumeo instructors for developing an awesome feel.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/larnell-lewis.png?v=1513185407"
        },
        {
          "id": 202287,
          "name": "Sarah Thawer",
          "biography": "Sarah Thawer has been making a name for herself since setting foot on stage to perform for the very first time, when she was only 6 years old. A self-taught drummer at heart, Sarah has found great success by embracing the world of formal education, by studying jazz and world music at York University where she was the recipient of the Oscar Peterson Scholarship, the highest award given by the institution, in addition to graduating with the Summa Cum Laude distinction. All her accolades and work ethic have taken her to share a stage with world-class artists including AR Rahman, Ruth B, Jane Bunnett and Maqueque, Del Hartley, and D’bi and the 333, just to name a few.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/sarah-thawer.jpg"
        }
      ]
    },
    {
      "id": 241291,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/advanced-styles-musical-decisions",
      "published_on": "2021/03/01 20:00:00",
      "description": "Level 9 covers a wide range of advanced material. From Afro-Cuban styles like mambo, nanigo, and songo to advanced independence concepts like rhythmic layering and sticking interpretations, this level will definitely challenge you. Here, you’ll also spend some time learning how to make musical decisions for any musical situation you’re in. Whether you’re jamming with a guitarist in your basement, playing in a wedding cover band, or touring the world playing stadiums with a rock band, it’s important to know how to make musical decisions for your specific scenario. ",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241291-card-thumbnail-1577145017.jpg",
      "position": 9,
      "progress_percent": 0,
      "title": "Advanced Styles & Musical Decisions",
      "instructor": [
        {
          "id": 31877,
          "name": "Dave Atkinson",
          "biography": "Drummer for the Canadian rock-band YUCA, Dave Atkinson is one of our most popular instructors. Teaching for over eight years now, he has helped thousands of students reach their goals through FreeDrumLessons.com, DrumLessons.com, and the instructional programs “Drum Play-Along System” and “Bass Drum Secrets”. A precise double-bass drummer and practice-pad lover, Dave brings a fresh outlook to the world of education through his hybrid teaching style - combining the best that formal training has to offer, with the creativity and resourcefulness of a self-taught drummer.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/dave-avatar.jpg"
        },
        {
          "id": 220809,
          "name": "Steve Lyman",
          "biography": null,
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/220809-avatar-1569523377.jpg"
        },
        {
          "id": 31978,
          "name": "John Wooton",
          "biography": "John Wooton is a world renowned marching percussion clinician. He's been associated with five P.A.S.I.C. Marching Percussion Forum champions. He's won several individual snare drum titles including the Percussive Arts Society National Championship and the Drum Corps Midwest Championship. John is currently the Director of Percussion Studies at The University of Southern Mississippi in Hattiesburg, Miss. He's released many books including \"Dr. Throwdown's Rudimental Remedies\", \"The Drummer's Rudimental Reference Book\", and others. John has performed clinics and recitals for Days of Percussion and marching percussion all over the United States, Canada, England, Argentina, Brazil, and Puerto Rico.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/john-wooton.png?v=1513185407"
        },
        {
          "id": 31964,
          "name": "Bruce Becker",
          "biography": "Drummer, educator, and producer Bruce Becker is the founding member and drummer of the David Becker Tribune. Bruce spent a long period of time from 1977 to 1984 studying with Freddie Gruber, which has influenced the past three decades of his overall drum education approach and teaching methods. His reputation has caused a vast amount of drummers to seek his teachings such as David Garibaldi, Mark Schulman, Daniel Glass, Glen Sobel, and many more. Bruce's drumming has been heard across a wide variety of television such as Monday Night Football, Entertainment Tonight, The Weather Channel, and advertisements.",
          "head_shot_picture_url": "https://d1923uyy6spedc.cloudfront.net/1514589819-bruce.jpg"
        },
        {
          "id": 31922,
          "name": "Mark Kelso",
          "biography": "Mark Kelso is a talented musician, composer, and producer who is currently head of percussion at Humber College in Toronto. Mark's ability to play a wide variety of musical styles has scored him gigs with artists such as Michael Buble, Shania Twain, Gino Vanelli, and Pete Townsend. Mark has been featured in ​Drums ETC., ​​Canadian Musician, ​and ​DRUM!​ magazine and has also been a featured clinician for many different drum festivals such as Montreal Drumfest, Musicfest, and Percussive Arts Society International Convention (PASIC).",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/mark-kelso.png?v=1513185407"
        },
        {
          "id": 31895,
          "name": "Larnell Lewis",
          "biography": "Larnell Lewis is a versatile and sought-after drummer, composer, producer, educator, and clinician, who has performed with Snarky Puppy, Fred Hammond, Jully Black, and Glen Lewis. A professor at Humber College's Faculty of Music, where as a student he received the Oscar Peterson Award for “Outstanding Achievement in Music”, Larnell is the ultimate groove master and one of the go-to Drumeo instructors for developing an awesome feel.",
          "head_shot_picture_url": "https://s3.amazonaws.com/drumeo-assets/instructors/larnell-lewis.png?v=1513185407"
        }
      ]
    },
    {
      "id": 241292,
      "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-levels/drumeo-method/go-anywhere-on-the-drums",
      "published_on": "2021/04/01 19:00:00",
      "description": "The final level of the Drumeo Method gives you a taste of what else is out there in the world of drumming. Like the title states, Level 10 prepares you to “go anywhere on the drums”. You’ll learn about advanced rhythmic concepts like metric modulation, polyrhythms, and polymeters, hybrid rudiments, sixteenth note odd meters, subdivisions like quintuplets and septuplets, and different types of soloing. All of this paired with an entire “Next Steps” section will help you know exactly where to go next as you move forward into the rest of your drumming journey.",
      "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/241292-card-thumbnail-1577145021.jpg",
      "position": 10,
      "progress_percent": 0,
      "title": "Go Anywhere On The Drums",
      "instructor": []
    }
  ],
  "lesson_rank": 1,
  "progress_percent": 10,
  "video_playback_endpoints": [
    {
      "file": "https://player.vimeo.com/external/382231267.sd.mp4?s=ba1acc6f413fbd5ba488bc939214ddb1e84663f5&profile_id=139&oauth2_token_id=1284792283",
      "width": 426,
      "height": 240
    },
    {
      "file": "https://player.vimeo.com/external/382231267.sd.mp4?s=ba1acc6f413fbd5ba488bc939214ddb1e84663f5&profile_id=164&oauth2_token_id=1284792283",
      "width": 640,
      "height": 360
    },
    {
      "file": "https://player.vimeo.com/external/382231267.sd.mp4?s=ba1acc6f413fbd5ba488bc939214ddb1e84663f5&profile_id=165&oauth2_token_id=1284792283",
      "width": 960,
      "height": 540
    },
    {
      "file": "https://player.vimeo.com/external/382231267.hd.mp4?s=66d2df0953099f12bd12348ccdb249dcb33a513d&profile_id=174&oauth2_token_id=1284792283",
      "width": 1280,
      "height": 720
    },
    {
      "file": "https://player.vimeo.com/external/382231267.hd.mp4?s=66d2df0953099f12bd12348ccdb249dcb33a513d&profile_id=175&oauth2_token_id=1284792283",
      "width": 1920,
      "height": 1080
    },
    {
      "file": "https://player.vimeo.com/external/382231267.hd.mp4?s=66d2df0953099f12bd12348ccdb249dcb33a513d&profile_id=170&oauth2_token_id=1284792283",
      "width": 2560,
      "height": 1440
    },
    {
      "file": "https://player.vimeo.com/external/382231267.hd.mp4?s=66d2df0953099f12bd12348ccdb249dcb33a513d&profile_id=172&oauth2_token_id=1284792283",
      "width": 3840,
      "height": 2160
    }
  ],
  "banner_button_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/243158",
  "banner_background_image": "https://d1923uyy6spedc.cloudfront.net/241247-header-image-1583450010.jpg",
  "length_in_seconds": "255",
  "next_lesson": {
    "id": 243158,
    "type": "learning-path-lesson",
    "published_on": "2020/01/31 22:00:00",
    "completed": false,
    "started": true,
    "progress_percent": 0,
    "is_added_to_primary_playlist": false,
    "title": "The Importance Of Drum Theory",
    "length_in_seconds": "352",
    "thumbnail_url": "https://d1923uyy6spedc.cloudfront.net/243158-card-thumbnail-1593030817.png",
    "status": "published",
    "mobile_app_url": "https://staging.drumeo.com/laravel/public/musora-api/learning-path-lessons/243158"
  }
}
```
