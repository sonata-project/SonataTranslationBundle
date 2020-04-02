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

namespace Sonata\TranslationBundle\Tests\Twig\Extension;

use Sonata\TranslationBundle\Twig\Extension\IntlExtension;
use Twig\Test\IntegrationTestCase;

class IntlExtensionTest extends IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new IntlExtension(),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }
}
