<?php

namespace Railroad\MusoraApi\Entities;

use Railroad\Railcontent\Entities\ContentEntity;

class NextPreviousContentVO
{
    /**
     * @var ContentEntity|null
     */
    private $nextLesson = null;

    /**
     * @var ContentEntity|null
     */
    private $previousLesson = null;

    /**
     * NextPreviousContentVO constructor.
     *
     * @param  ContentEntity|null  $nextLesson
     * @param  ContentEntity|null  $previousLesson
     */
    public function __construct($nextLesson, $previousLesson)
    {
        $this->nextLesson = $nextLesson;
        $this->previousLesson = $previousLesson;
    }

    /**
     * @return ContentEntity|null
     */
    public function getNextLesson()
    {
        return $this->nextLesson;
    }

    /**
     * @return ContentEntity|null
     */
    public function getPreviousLesson()
    {
        return $this->previousLesson;
    }
}