<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Model;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
interface TranslatableInterface
{
    /**
     * @param string $locale
     */
    public function setLocale($locale);

    /**
     * @return string
     */
    public function getLocale();
}
