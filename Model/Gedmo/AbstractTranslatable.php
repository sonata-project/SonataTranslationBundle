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

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * This is your based class if you want to use default gedmo translation with everything in the same table
 * Not recommended if you have a lot of translations
 * (just brings Gedmo locale mapping).
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @see https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md
 */
abstract class AbstractTranslatable extends \Sonata\TranslationBundle\Model\AbstractTranslatable
{
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     */
    protected $locale;
}
