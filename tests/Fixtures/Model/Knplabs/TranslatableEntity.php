<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Model;
use Sonata\TranslationBundle\Model\TranslatableInterface;

class TranslatableEntity implements TranslatableInterface
{
    use Model\Translatable\Translatable;

    private $id;

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @return integer
     */
    public function getId()
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
