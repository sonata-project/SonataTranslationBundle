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

namespace Sonata\TranslationBundle\Enum;

/**
 * @author Omar Jbara <omar.jbara2@gmail.com>
 */
final class TranslationFilterMode
{
    public const KNPLABS = 'knplabs';
    public const GEDMO = 'gedmo';

    public const AVAILABLE_FILTER_TYPES = [
        self::GEDMO,
        self::KNPLABS,
    ];
}
