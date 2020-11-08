<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Model;
use Sonata\TranslationBundle\Model\TranslatableInterface;

class TranslatableEntity implements TranslatableInterface
{
    use Model\Translatable\Translatable;

    /**
     * @var int|null
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLocale($locale)
    {
        $this->setCurrentLocale($locale);
    }

    public function getLocale()
    {
        return $this->getCurrentLocale();
    }
}
