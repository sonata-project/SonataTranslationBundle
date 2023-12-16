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

namespace Sonata\TranslationBundle\Tests\App\Provider;

use Knp\DoctrineBehaviors\Contract\Provider\UserProviderInterface;

final class DummyUserProvider implements UserProviderInterface
{
    /**
     * @return object|string|null
     */
    public function provideUser()
    {
        return null;
    }

    public function provideUserEntity(): ?string
    {
        return null;
    }
}
