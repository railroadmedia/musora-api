<?php

namespace Railroad\MusoraApi\Controllers;

use Illuminate\Routing\Controller;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;

class ContentController extends Controller
{

    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @var VimeoVi
     */
    private $vimeoVideoSourcesDecorator;



    /**
     * @param $contentId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getContent($contentId, \Illuminate\Http\Request $request)
    {
        $content = $this->contentService->getById($contentId);

        if(!$content){
            return response()->json();
        }

        $isDownload = $request->get('download', false);

        CommentRepository::$availableContentId = $content['id'];
        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $content['comments'] = $comments['results'];
        $content['total_comments'] = $comments['total_results'];

        return response()->json(['data' => [$content]]);
    }
}
