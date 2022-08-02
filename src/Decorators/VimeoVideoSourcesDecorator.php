<?php

namespace Railroad\MusoraApi\Decorators;

use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Railroad\Railcontent\Services\ConfigService;
use Railroad\Railcontent\Support\Collection;
use Vimeo\Vimeo;

class VimeoVideoSourcesDecorator extends ModeDecoratorBase
{
    /**
     * @var Vimeo
     */
    private $vimeo;

    /**
     * @var Repository
     */
    private $cache;

    const CACHE_KEY_PREFIX = 'drumeo_vimeo_video_data_';

    /**
     * ContentVimeoVideoDecorator constructor.
     *
     * @param Vimeo $vimeo
     */
    public function __construct()
    {
        $clientId = ConfigService::$videoSync['vimeo'][ConfigService::$brand]['client_id'];
        $clientSecret = ConfigService::$videoSync['vimeo'][ConfigService::$brand]['client_secret'];
        $accessToken = ConfigService::$videoSync['vimeo'][ConfigService::$brand]['access_token'];

        $vimeo = new Vimeo($clientId, $clientSecret);
        $vimeo->setToken($accessToken);

        $this->vimeo = $vimeo;

        $this->cache = app()->make(Repository::class);
    }

    public function decorate(Collection $contents)
    {
        $contentResults = $contents->toArray();

        foreach ($contentResults as $contentIndex => $content) {
            if (array_key_exists('fields', $content)) {
                foreach ($content['fields'] as $field) {

                    if ($field['key'] === 'video' && $field['value']['type'] == 'vimeo-video') {

                        $prefix = '';

                        if ($field['key'] === 'qna_video') {
                            $prefix = 'qna_';
                        }

                        foreach ($field['value']['fields'] as $videoField) {
                            if ($videoField['key'] === 'vimeo_video_id') {

                                // cache
                                $response = $this->cache->get(self::CACHE_KEY_PREFIX . $videoField['value']);
                                $captions = [];

                                if (empty($response['body']['files']) || empty($response['body']['pictures']['sizes'])) {
                                    $response = $this->vimeo->request(
                                        '/me/videos/' . $videoField['value'],
                                        [],
                                        'GET'
                                    );

                                    $textTractResponse = $this->vimeo->request('/videos/' . $videoField['value'] . '/texttracks', [], 'GET');

                                    $response['body']['text-tracks'] = $textTractResponse['body']['data'] ?? [];

                                    if (!empty($response['body']['download'][0]['expires'])) {
                                        $expirationDate =
                                            Carbon::parse($response['body']['download'][0]['expires'])
                                                ->diffInMinutes(
                                                    Carbon::now()
                                                ) - 30;

                                        $this->cache->put(
                                            self::CACHE_KEY_PREFIX . $videoField['value'],
                                            $response,
                                            $expirationDate
                                        );
                                    }
                                }

                                if (!empty($response['body']['text-tracks'])) {
                                    foreach ($response['body']['text-tracks'] as $textTrackData) {
                                        $captions[] = $textTrackData['link'];
                                    }
                                }

                                if (!empty($response['body']['files'])) {
                                    foreach ($response['body']['files'] as $fileData) {
                                        if (isset($fileData['height'])) {
                                            $contentResults[$contentIndex]
                                            [$prefix . 'video_playback_endpoints'][$fileData['height']] = [
                                                'file' => $fileData['link_secure'],
                                                'width' => $fileData['width'],
                                                'height' => $fileData['height'],
                                            ];

                                            $response['body']['pictures']['sizes'] = array_combine(
                                                array_column($response['body']['pictures']['sizes'] ?? [], 'height'),
                                                $response['body']['pictures']['sizes'] ?? []
                                            );

                                            $sizes = array_keys(
                                                $response['body']['pictures']['sizes'] ?? []
                                            );

                                            $sizes = array_filter(
                                                $sizes,
                                                function ($element) {
                                                    if ($element > 720) {
                                                        return false;
                                                    }

                                                    return true;
                                                }
                                            );

                                            $contentResults[$contentIndex]
                                            [$prefix . 'video_poster_image_url'] = (!empty($sizes))?$response['body']['pictures']
                                                ['sizes'][max($sizes)]['link'] ?? '':'';

                                            ksort(
                                                $contentResults[$contentIndex]
                                                [$prefix . 'video_playback_endpoints']
                                            );
                                        }

                                        if ($fileData['quality'] === 'hls') {
                                            $contentResults[$contentIndex]['hlsManifestUrl'] = $fileData['link'];
                                        }

                                        // Removing captions from response due to error on FE.
                                        // Change back once resolved.
                                        $contentResults[$contentIndex]['captions'] = null;
//                                        $contentResults[$contentIndex]['captions'] = $captions;
                                    }

                                    $contentResults[$contentIndex]
                                    [$prefix . 'video_playback_endpoints'] = array_values(
                                        $contentResults[$contentIndex]
                                        [$prefix . 'video_playback_endpoints']
                                    );
                                }
                            }
                        }
                    }
                }

            }
        }

        return new Collection($contentResults);
    }
}
