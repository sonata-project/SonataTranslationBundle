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

@trigger_error(
    'The '.__NAMESPACE__.'\PersonalTranslatable class is deprecated since version 2.1 and will be removed in 3.0.'.
    'Use the '.__NAMESPACE__.'\PersonalTranslatableTrait class instead.',
    E_USER_DEPRECATED
);

/**
 * If you don't want to use trait, you can extend AbstractPersonalTranslatable instead.
 *
 * NEXT_MAJOR: remove this trait.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @deprecated since version 2.1 and will be removed in 3.0
 */
trait PersonalTranslatable
{
    use PersonalTranslatableTrait;
}
