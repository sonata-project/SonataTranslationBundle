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
     * Returns object id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->setCurrentLocale($locale);

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->getCurrentLocale();
    }
}
