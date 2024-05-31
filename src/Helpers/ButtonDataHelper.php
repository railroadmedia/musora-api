<?php

namespace Railroad\MusoraApi\Helpers;

class ButtonDataHelper
{
    /**
     * @param $primaryCtaUrl
     * @param $text
     * @return array
     */
    public static function getButtonData($primaryCtaUrl, $text): array
    {
        $pageTypeMapping = config('musora-api.pageTypeMapping', []);
        $pageType = null;
        $pageParams = [];
        $buttonData = [];

        if (filter_var($primaryCtaUrl, FILTER_VALIDATE_URL)) {
            $ctaRequest = \Request::create($primaryCtaUrl);
            $segments = $ctaRequest->segments();

            $lastSegment = last($ctaRequest->segments());
            $routeAction = app('router')->getRoutes()->match(app('request')->create($primaryCtaUrl))->getAction();

            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.content-type-catalog' && $lastSegment == 'drum-fest-international-2022') {
                $pageType = 'ShowOverview';
                $pageParams['keyExtractor'] = $lastSegment;
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.workouts.challenges') {
                $pageType = 'SeeAll2';
                $pageParams['title'] = 'All Challenges';
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.workout.challenge') {
                $pageType = 'PackOverview';
                $pageParams['id'] = $lastSegment;
                $pageParams['type'] = "Lesson";
                $pageParams['isChallenge'] = true;
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.workouts') {
                $pageType = 'Workouts';
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.content.first-level') {
                $pageType = 'Lesson';
                $pageParams['id'] = $lastSegment;
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.content.fourth-level') {
                $pageType = 'Lesson';
                $pageParams['id'] = $lastSegment;
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.workout.challenge.workout') {
                $pageType = 'Lesson';
                $pageParams['id'] = $lastSegment;
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.user.playlist') {
                $pageType = 'Playlist';
                $pageParams['playlistId'] = $lastSegment;
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.live') {
                $pageType = 'Schedule';
                $pageParams['showLiveEvents'] = true;
            }
            if (isset($routeAction['as']) && $routeAction['as'] == 'platform.schedule') {
                $pageType = 'Schedule';
                $pageParams['showLiveEvents'] = false;
            }
            if ((isset($routeAction['as']) && $routeAction['as'] == 'platform.home.create-playlist-window') && (config('musora-api.api.version') == 'v4')) {
                $pageType = 'PlaylistCRUD';
                $pageParams['mode'] = 'Create';
            }
            if (isset($pageTypeMapping[$lastSegment])) {
                $pageType = $pageTypeMapping[$lastSegment];
            } elseif (in_array('enrollment', $segments)) {
                $pageType = 'CohortLandingPage';
                $pageParams['slug'] = $lastSegment;
            } elseif (is_numeric($lastSegment) && in_array('coaches', $segments)) {
                $pageType = 'CoachOverview';
                $pageParams['id'] = $lastSegment;
            } elseif (is_numeric($lastSegment) && in_array('packs', $segments)) {
                $pageType = 'PackOverview';
                $pageParams['id'] = $lastSegment;
                $pageParams['type'] = "Lesson";
            } elseif (is_numeric($lastSegment) && in_array('courses', $segments)) {
                $pageType = 'CourseOverview';
                $pageParams['id'] = $lastSegment;
            } elseif (is_numeric($lastSegment) &&
                in_array('forums', $segments) &&
                in_array('jump-to-post', $segments)) {
                $pageType = 'Forum';
                $pageParams['postId'] = $lastSegment;
            } elseif (is_numeric($lastSegment) && in_array('forums', $segments)) {
                $pageType = 'Forum';
                $pageParams['threadId'] = $lastSegment;
            }
        }

        $buttonData['url'] = $primaryCtaUrl;
        $buttonData['link'] = $text;
        if ($pageType) {
            $buttonData['page_type'] = $pageType;
        }
        if (!empty($pageParams)) {
            $buttonData['page_params'] = $pageParams;
        }

        return $buttonData;
    }
}
