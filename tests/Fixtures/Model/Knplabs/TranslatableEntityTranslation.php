<?php

namespace Sonata\TranslationBundle\Tests\Functional\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Model;

class TranslatableEntityTranslation
{
    use Model\Translatable\Translation;

    private $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }
}
