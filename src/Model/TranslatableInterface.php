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

namespace Sonata\TranslationBundle\Model;

/**
 * @deprecated since sonata-project/translation-bundle 2.x, to be removed in 3.0.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
interface TranslatableInterface
{
    /**
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale);

    /**
     * @return string|null
     */
    public function getLocale();
}
