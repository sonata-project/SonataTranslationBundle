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
class User implements UserInterface
{
    public function getRoles(): array
    {
        return [];
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return 'username';
    }

    public function eraseCredentials(): void
    {
    }
}
