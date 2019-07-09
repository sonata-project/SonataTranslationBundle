<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Traits\Gedmo;

use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * If you don't want to use trait, you can extend AbstractPersonalTranslatable instead.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
trait PersonalTranslatableTrait
{
    use TranslatableTrait;

    /**
     * @return ArrayCollection|AbstractPersonalTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param $field
     * @param $locale
     *
     * @return string|null
     */
    public function getTranslation($field, $locale)
    {
        foreach ($this->getTranslations() as $translation) {
            if (0 === strcmp($translation->getField(), $field) && 0 === strcmp($translation->getLocale(), $locale)) {
                return $translation->getContent();
            }
        }
    }

    /**
     * @return $this
     */
    public function addTranslation(AbstractPersonalTranslation $translation)
    {
        if (!$this->translations->contains($translation)) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }

        return $this;
    }
}
