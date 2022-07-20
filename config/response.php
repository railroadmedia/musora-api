<?php

return [
    'v1' =>
        [
        'catalogues' => [
            'id',
            'data.thumbnail_url',
            'type',
            'published_on',
            'status',
            'fields.title',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'instructors',
            'fields.artist',
            'fields.style',
            'fields.video.fields.length_in_seconds',
            'parent_id',
            'fields.name',
            'data.head_shot_picture_url',
            'isLive',
            'is_liked_by_current_user',
            'like_count',
        ],

        'instructor' => [
            'id',
            'fields.name',
            'data.biography',
            'data.head_shot_picture_url',
        ],

        'instructor-filter' => [
            'id',
            'fields.name',
            'data.head_shot_picture_url',
            'type',
        ],

        'coach' => [
            'id',
            'fields.name',
            'biography',
            'data.head_shot_picture_url',
            'lessons' => [
                'id',
                'type',
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'status',
                'published_on',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
                'is_liked_by_current_user',
                'parent_id',
            ],
            'lessons_filter_options',
            'total_comments',
            'comments',
            'duration_in_seconds',
            'total_xp',
        ],

        'coach-filter' => [
            'id',
            'fields.name',
            'data.head_shot_picture_url',
            'type',
        ],

        'pack-bundle' => [
            'id',
            'type',
            'fields.title',
            'data.description',
            'completed',
            'started',
            'progress_percent',
            'is_owned',
            'full_price',
            'price',
            'pack_logo',
            'apple_product_id',
            'google_product_id',
            'lessons' => [
                'id',
                'type',
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'status',
                'published_on',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
                'is_liked_by_current_user',
                'parent_id',
            ],
            'current_lesson_index',
            'next_lesson' => [
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'id',
                'type',
                'status',
                'published_on',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
                'is_liked_by_current_user',
                'parent_id',
            ],
        ],

        'pack-bundle-lesson' => [
            'id',
            'type',
            'fields.title',
            'data.description',
            'completed',
            'started',
            'progress_percent',
            'related_lessons' => [
                'id',
                'type',
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'status',
                'published_on',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
                'is_liked_by_current_user',
                'parent_id',
            ],
            'next_lesson' => [
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'id',
                'type',
                'status',
                'published_on',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
                'is_liked_by_current_user',
                'parent_id',
            ],
            'previous_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
            ],
            'parent',
            'next_content_type',
            'total_comments',
            'comments',
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'fields.video.fields.length_in_seconds',
            'xp',
            'total_xp',
            'xp_bonus',
            'published_on',
            'is_added_to_primary_playlist',
            'is_liked_by_current_user',
            'like_count',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
        ],

        'course' => [
            'id',
            'data.thumbnail_url',
            'type',
            'published_on',
            'status',
            'fields.title',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'instructors',
            'length_in_seconds',
            'data.description',
            'xp',
            'xp_bonus',
            'duration',
            'banner_button_url',
            'is_liked_by_current_user',
            'like_count',
            'current_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'lessons' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            '*fields.instructor',
        ],

        'course-part' => [
            'id',
            'type',
            'published_on',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'fields.title',
            'length_in_seconds',
            'data.thumbnail_url',
            'total_comments',
            'comments',
            'xp',
            'xp_bonus',
            'instructor',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'data.description',
            'data.captions',
            'like_count',
            'is_liked_by_current_user',
            'chapters',
            'user_progress',
            'resources',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
            'related_lessons' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'previous_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'parent',
        ],

        'play-along' => [
            'id',
            'type',
            'published_on',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'fields.title',
            'length_in_seconds',
            'data.thumbnail_url',
            'comments',
            'xp',
            'xp_bonus',
            '*fields.instructor',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'data.description',
            'data.captions',
            'data.mp3_no_drums_no_click_url',
            'data.mp3_no_drums_yes_click_url',
            'data.mp3_yes_drums_no_click_url',
            'data.mp3_yes_drums_yes_click_url',
            'like_count',
            'is_liked_by_current_user',
            'chapters',
            'user_progress',
            'resources',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'previous_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'related_lessons' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'parent',
            'fields.artist',
            'fields.style',
        ],

        'song' => [
            'id',
            'type',
            'published_on',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'fields.title',
            'length_in_seconds',
            'data.thumbnail_url',
            'comments',
            'xp',
            'xp_bonus',
            '*fields.instructor',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'data.description',
            'data.captions',
            'like_count',
            'is_liked_by_current_user',
            'chapters',
            'user_progress',
            'resources',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
            'next_lesson',
            'previous_lesson',
            'related_lessons',
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'parent',
            'fields.artist',
            'fields.style',
        ],

        'coach-stream' => [
            'id',
            'type',
            'published_on',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'fields.title',
            'length_in_seconds',
            'data.thumbnail_url',
            'comments',
            'xp',
            'xp_bonus',
            '*fields.instructor',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'data.description',
            'data.captions',
            'like_count',
            'is_liked_by_current_user',
            'chapters',
            'user_progress',
            'resources',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'previous_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'related_lessons' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'parent',
            'fields.artist',
            'fields.style',
        ],

        'top-header-pack' => [
            'id',
            'type',
            'started',
            'completed',
            'is_new',
            'mobile_app_url',
            'next_lesson_url',
            'bundle_count',
            'thumbnail',
            'pack_logo',
            'full_price',
            'price',
            'is_owned',
        ],

        'my-packs' => [
            'id',
            'type',
            'is_new',
            'mobile_app_url',
            'bundle_count',
            'thumbnail',
            'pack_logo',
        ],

        'more-packs' => [
            'id',
            'type',
            'is_new',
            'mobile_app_url',
            'bundle_count',
            'thumbnail',
            'pack_logo',
            'is_locked',
            'price',
        ],

        'live-schedule' => [
            'id',
            'type',
            'fields.title',
            'fields.live_event_start_time',
            'fields.live_event_end_time',
            'data.thumbnail_url',
            'published_on',
            'is_added_to_primary_playlist',
            'fields.length_in_seconds',
            'parent_id',
            'completed',
            'started',
            'instructors',
            'isLive',
        ],

        'live' => [
            'id',
            'type',
            'isLive',
            'fields.title',
            'fields.live_event_start_time',
            'fields.live_event_end_time',
            'instructor_head_shot_picture_url',
            'is_added_to_primary_playlist',
            'data.thumbnail_url',
            'instructors',
            'chatRollEmbedUrl',
            'chatRollViewersNumberClass',
            'youtube_video_id',
            'chatRollStyle',
            'apiKey',
            'chatChannelName' ,
            'questionsChannelName',
            'token',
            'userId',
        ],

        'pack' => [
            'id',
            'type',
            'fields.title',
            'data.description',
            'mobile_app_url',
            'completed',
            'started',
            'progress_percent',
            'is_owned',
            'thumbnail',
            'pack_logo',
            'apple_product_id',
            'google_product_id',
            'bundles' => [
                'fields.title',
                'data.thumbnail_url',
                'id',
                'progress_percent',
                'mobile_app_url',
                'lesson_count',
                'like_count',
            ],
            "current_lesson_index",
            "bundle_number",
            "next_lesson" => [
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'id',
                'type',
                'status',
                'published_on',
                'parent_id',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
            ],
        ],

        'semester-pack' => [
            'id',
            'type',
            'fields.title',
            'data.description',
            'url',
            'mobile_app_url',
            'completed',
            'started',
            'progress_percent',
            'is_owned',
            'thumbnail',
            'pack_logo',
            'apple_product_id',
            'google_product_id',
            'lessons' => [
                'fields.title',
                'data.thumbnail_url',
                'id',
                'progress_percent',
                'mobile_app_url',
                'lesson_count',
                'like_count',
                'fields.video.fields.length_in_seconds',
                'is_added_to_primary_playlist',
            ],
            "current_lesson_index",
            "next_lesson" => [
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'id',
                'type',
                'status',
                'published_on',
                'parent_id',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
            ],
        ],

        'semester-pack-lesson' => [
            'id',
            'type',
            'fields.title',
            'data.description',
            'completed',
            'started',
            'progress_percent',
            'related_lessons' => [
                'id',
                'type',
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'status',
                'published_on',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
                'is_liked_by_current_user',
                'parent_id',
            ],
            'next_lesson' => [
                'fields.title',
                'fields.artist',
                'fields.style',
                'data.thumbnail_url',
                'id',
                'type',
                'status',
                'published_on',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'mobile_app_url',
                'fields.video.fields.length_in_seconds',
                'is_liked_by_current_user',
                'parent_id',
            ],
            'previous_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
            ],
            'parent',
            'next_content_type',
            'total_comments',
            'comments',
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'fields.video.fields.length_in_seconds',
            'xp',
            'total_xp',
            'xp_bonus',
            'published_on',
            'is_added_to_primary_playlist',
            'is_liked_by_current_user',
            'like_count',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
        ],

        'assignment' => [
            'id',
            'xp',
            'user_progress',
            'fields.title',
            'fields.soundslice_slug',
            'data.description',
            '*data.sheet_music_image_url',
            'data.timecode',
        ],

        'comment' => [
            'is_liked',
            'like_count',
            'user_id',
            'id',
            'replies',
            'user' => [
                'fields.profile_picture_image_url',
                'xp',
                'display_name',
                'xp_level',
            ],
            'comment',
            'created_on',
        ],

        'learning-path' => [
            'id',
            'started',
            'completed',
            'level_rank',
            'fields.title',
            'fields.video.fields.vimeo_video_id',
            'data.description',
            'data.thumbnail_url',
            'published_on',
            'levels' => [
                'id',
                'mobile_app_url',
                'published_on',
                'data.description',
                'data.thumbnail_url',
                'position',
                'progress_percent',
                'fields.title',
                '*fields.instructor',
            ],
            'lesson_rank',
            'progress_percent',
            'video_playback_endpoints',
            'banner_button_url',
            'banner_background_image',
            'fields.video.fields.length_in_seconds',
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
                'status',
                'mobile_app_url',
            ],
        ],

        'learning-path-level' => [
            'id',
            'type',
            'fields.title',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            '*fields.instructor',
            'data.description',
            'data.thumbnail_url',
            'mobile_app_url',
            'level_number',
            'banner_background_image',
            'total_xp',
            'started',
            'completed',
            'progress_percent',
            'video_playback_endpoints',
            'length_in_seconds',
            'banner_button_url',
            'is_added_to_primary_playlist',
            'published_on',
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
                'status',
                'mobile_app_url',
            ],
            'courses' => [
                'id',
                'fields.title',
                'mobile_app_url',
                'level_rank',
                'data.thumbnail_url',
                'is_added_to_primary_playlist',
                'progress_percent',
            ],
        ],

        'learning-path-course' => [
            'id',
            'type',
            'is_added_to_primary_playlist',
            'started',
            'completed',
            'fields.title',
            'mobile_app_url',
            'data.thumbnail_url',
            'data.description',
            'progress_percent',
            'xp',
            'total_xp',
            'duration',
            'banner_button_url',
            'published_on',
            'is_liked_by_current_user',
            'like_count',
            'total_length_in_seconds',
            'level_position',
            'course_position',
            '*fields.instructor',
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
                'status',
                'mobile_app_url',
            ],
            'lessons' => [
                'id',
                'type',
                'published_on',
                'started',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
                'mobile_app_url',
            ],
        ],

        'learning-path-lesson' => [
            'id',
            'type',
            'fields.title',
            'mobile_app_url',
            'fields.video.fields.length_in_seconds',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'instructor',
            'data.description',
            'data.thumbnail_url',
            'chapters',
            'total_xp',
            'xp',
            'user_progress',
            'published_on',
            'next_lesson_id',
            'next_lesson_thumbnail_url',
            'next_lesson_title',
            'next_lesson_url',
            'nextLessonLengthInMinValue',
            'is_last_incomplete_lesson_from_course',
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
                'status',
                'mobile_app_url',
            ],
            'prev_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
                'status',
                'mobile_app_url',
            ],
            'current_course' => [
                'id',
                'fields.title',
                'fields.xp',
                'data.thumbnail_url',
            ],
            'next_course' => [
                'id',
                'fields.title',
                'mobile_app_url',
                'level_rank',
                'data.thumbnail_url',
                'lesson_count',
            ],
            'current_level' => [
                'id',
                'fields.title',
                'fields.xp',
                'level_number',
                'data.thumbnail_url',
            ],
            'next_level' => [
                'id',
                'fields.title',
                'mobile_app_url',
                'level_number',
                'data.thumbnail_url',
                'published_on',
                'is_added_to_primary_playlist',
            ],
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
            'is_liked_by_current_user',
            'like_count',
            'is_added_to_primary_playlist',
            'level_position',
            'course_position',
            'related_lessons' => [
                'id',
                'type',
                'published_on',
                'started',
                'completed',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'fields.video.fields.length_in_seconds',
                'data.thumbnail_url',
                'mobile_app_url',
            ],
            'comments',
            'video_playback_endpoints',
            'captions',
            'last_watch_position_in_seconds',
            'last_incomplete_lesson_from_course',
            'is_last_incomplete_course_from_level',
            'resources',
        ],

        'student-focus' => [
            'id',
            'type',
            'published_on',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'fields.title',
            'length_in_seconds',
            'data.thumbnail_url',
            'total_comments',
            'comments',
            'xp',
            'xp_bonus',
            '*fields.instructor',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'data.description',
            'data.captions',
            'like_count',
            'is_liked_by_current_user',
            'chapters',
            'user_progress',
            'resources',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
            'related_lessons' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'previous_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'parent',
        ],

        'show-lesson' => [
            'id',
            'type',
            'published_on',
            'completed',
            'started',
            'progress_percent',
            'is_added_to_primary_playlist',
            'fields.title',
            'length_in_seconds',
            'data.thumbnail_url',
            'total_comments',
            'comments',
            'xp',
            'xp_bonus',
            '*fields.instructor',
            'fields.video.fields.vimeo_video_id',
            'fields.video.fields.youtube_video_id',
            'fields.video.fields.length_in_seconds',
            'data.description',
            '*data.captions',
            'like_count',
            'is_liked_by_current_user',
            'chapters',
            'user_progress',
            'resources',
            'assignments' => [
                'id',
                'xp',
                'user_progress',
                'fields.title',
                'fields.soundslice_slug',
                'data.description',
                '*data.sheet_music_image_url',
                'data.timecode',
            ],
            'related_lessons' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'next_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'previous_lesson' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
            ],
            'video_playback_endpoints',
            'last_watch_position_in_seconds',
            'parent',
        ],

        'download' => [
            'lessons' => [
                'id',
                'type',
                'published_on',
                'completed',
                'started',
                'progress_percent',
                'is_added_to_primary_playlist',
                'fields.title',
                'length_in_seconds',
                'data.thumbnail_url',
                'video_playback_endpoints',
                'last_watch_position_in_seconds',
                'fields.video.fields.vimeo_video_id',
                'fields.video.fields.youtube_video_id',
                'fields.video.fields.length_in_seconds',
                'resources',
                'assignments' => [
                    'id',
                    'xp',
                    'user_progress',
                    'fields.title',
                    'fields.soundslice_slug',
                    'data.description',
                    '*data.sheet_music_image_url',
                    'data.timecode',
                ],
                'comments',
                'related_lessons' => [
                    'id',
                    'type',
                    'fields.title',
                    'fields.artist',
                    'fields.style',
                    'data.thumbnail_url',
                    'status',
                    'published_on',
                    'completed',
                    'progress_percent',
                    'is_added_to_primary_playlist',
                    'mobile_app_url',
                    'length_in_seconds',
                    'is_liked_by_current_user',
                    'parent_id',
                ],
                'next_lesson' => [
                    'fields.title',
                    'fields.artist',
                    'fields.style',
                    'data.thumbnail_url',
                    'id',
                    'type',
                    'status',
                    'published_on',
                    'completed',
                    'progress_percent',
                    'is_added_to_primary_playlist',
                    'mobile_app_url',
                    'length_in_seconds',
                    'is_liked_by_current_user',
                    'parent_id',
                ],
                'previous_lesson' => [
                    'id',
                    'type',
                    'published_on',
                    'completed',
                    'started',
                    'progress_percent',
                    'is_added_to_primary_playlist',
                    'fields.title',
                    'length_in_seconds',
                    'data.thumbnail_url',
                ],
            ],
            '*fields.instructor',
        ],

        'profile' => [
            'id',
            'email',
            'display_name',
            'avatarUrl',
            'totalXp',
            'xpRank',
            'isEdge',
            'isEdgeExpired',
            'edgeExpirationDate',
            'isPackOlyOwner',
            'isAppleAppSubscriber',
            'isGoogleAppSubscriber',
        ],
    ],
];