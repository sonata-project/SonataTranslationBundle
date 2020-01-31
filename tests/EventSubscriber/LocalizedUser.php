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

namespace Sonata\TranslationBundle\Tests\EventSubscriber;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
class LocalizedUser extends User implements UserInterface
{
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }
}
