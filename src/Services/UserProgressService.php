<?php

namespace Railroad\MusoraApi\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Railroad\Railcontent\Repositories\ContentRepository;

class UserProgressService
{
    /**
     * @var ContentRepository
     */
    private $contentRepository;

    /**
     * UserProgressService constructor.
     *
     * @param ContentRepository $contentRepository
     */
    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * @param $id
     * @return array|Builder[]|\Illuminate\Support\Collection
     */
    public function getProgressOnPack($id)
    {
        return $this->contentRepository->connection()
            ->table('railcontent_content_hierarchy as ch_1')
            ->select(
                [
                    'ch_1.parent_id as pack_id',
                    'ch_2.parent_id as pack_bundle_id',
                    'ch_2.child_id as pack_bundle_lesson_id',

                    'up.state as state',
                    'up.progress_percent as progress_percent',
                ]
            )
            ->join('railcontent_content_hierarchy as ch_2', 'ch_2.parent_id', '=', 'ch_1.child_id')
            ->leftJoin(
                'railcontent_user_content_progress as up',
                function (JoinClause $join) {
                    $join->on(
                        'up.content_id',
                        '=',
                        'ch_2.child_id'
                    )
                        ->where(
                            function (Builder $builder) {
                                $builder->where('up.user_id', auth()->id());
                            }
                        );
                }
            )
            ->where('ch_1.parent_id', $id)
            ->get();
    }
}