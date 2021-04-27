<?php

namespace Railroad\MusoraApi\Tests\Decorators;

use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\Railcontent\Services\ContentHierarchyService;
use Railroad\Railcontent\Support\Collection;

class PackDecorator extends ModeDecoratorBase
{
    private $contentHierarchyService;

    /**
     * PackDecorator constructor.
     *
     * @param $contentHierarchyService
     */
    public function __construct(ContentHierarchyService $contentHierarchyService)
    {
        $this->contentHierarchyService = $contentHierarchyService;
    }

    /**
     * @param Collection $contents
     * @return Collection
     */
    public function decorate(Collection $contents)
    {
        // url
        $contentsOfType = $contents->where('type', 'pack');

        if ($contentsOfType->isEmpty()) {
            return $contents;
        }

        $childCounts = $this->contentHierarchyService->countParentsChildren(
            $contents->pluck('id')
                ->toArray()
        );

        foreach ($contentsOfType as $contentIndex => $content) {
            $contentsOfType[$contentIndex]['bundle_count'] = $childCounts[$content['id']] ?? 0;
        }

        if (self::$decorationMode !== self::DECORATION_MODE_MAXIMUM) {
            return $contentsOfType;
        }

        // everything else
        $packBundlesHierarchies = $this->contentHierarchyService->getByParentIds(
            $contents->pluck('id')
                ->toArray()
        );

        $packBundleLessonsHierarchies = $this->contentHierarchyService->getByParentIds(
            array_column($packBundlesHierarchies, 'child_id')
        );

        foreach ($contentsOfType as $contentIndex => $content) {
            if (in_array(
                $content['slug'],
                [
                    'learn-songs-faster',
                    '4-weeks-to-better-drum-fills',
                    'drumeo-festival-2020',
                    'electrify-your-drumming',
                    'the-ultimate-guide-to-recording-drums'
                ]
            )) {
                $contentsOfType[$contentIndex]['included_with_edge'] = true;
            }

            if (!isset($contentsOfType[$contentIndex]['xp'])) {
                $contentsOfType[$contentIndex]['xp'] = 0;
            }

            if (!isset($contentsOfType[$contentIndex]['xp_bonus'])) {
                $contentsOfType[$contentIndex]['xp_bonus'] = 0;
            }

            $contentsOfType[$contentIndex]['xp'] += $content->fetch(
                'fields.xp',
                config('xp_ranks.pack_content_completed')
            );
            $contentsOfType[$contentIndex]['xp_bonus'] += $content->fetch(
                'fields.xp',
                config('xp_ranks.pack_content_completed')
            );

            foreach ($packBundlesHierarchies as $packBundlesHierarchy) {
                if ($packBundlesHierarchy['parent_id'] == $content['id']) {
                    $contentsOfType[$contentIndex]['xp'] += config('xp_ranks.pack_bundle_content_completed');

                    foreach ($packBundleLessonsHierarchies as $packBundleLessonsHierarchy) {
                        if ($packBundleLessonsHierarchy['parent_id'] == $packBundlesHierarchy['child_id']) {
                            $contentsOfType[$contentIndex]['xp'] += config('xp_ranks.difficulty_xp_map.all');
                        }
                    }
                }
            }
        }

        return $contentsOfType;
    }
}