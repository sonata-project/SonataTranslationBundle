<?php

namespace Sonata\TranslationBundle\Tests\Functional\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Model;

class TranslatableEntityTranslation
{
    use Model\Translatable\Translation;

    /**
     * @var string|null
     */
    private $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
