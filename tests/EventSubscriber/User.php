<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Tests\EventSubscriber;

use Doctrine\Common\Cache\VoidCache;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
class User implements UserInterface
{
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function getRoles()
    {
        return [];
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
