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

/**
 * NEXT_MAJOR: Remove this file.
 *
 * If you don't want to use trait, you can extend AbstractTranslatable instead.
 *
 * @deprecated since version 2.x, to be removed in 3.0. Create your own trait instead.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
trait TranslatableTrait
{
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     */
    protected $locale;

    /**
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
