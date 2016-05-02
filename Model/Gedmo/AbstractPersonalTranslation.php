<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Model\Gedmo;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as GedmoAbstractPersonalTranslation;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @see https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md : Personal translations
 */
class AbstractPersonalTranslation extends GedmoAbstractPersonalTranslation
{
    /**
     * Convenient constructor.
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale = null, $field = null, $value = null)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }
}
