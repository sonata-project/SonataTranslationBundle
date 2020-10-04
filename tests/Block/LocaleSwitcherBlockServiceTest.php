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

namespace Sonata\TranslationBundle\Tests\Block;

use Sonata\BlockBundle\Test\BlockServiceTestCase;
use Sonata\TranslationBundle\Block\LocaleSwitcherBlockService;
use Twig\Environment;

final class LocaleSwitcherBlockServiceTest extends BlockServiceTestCase
{
    public function testDefaultSettings(): void
    {
        $environment = $this->createStub(Environment::class);

        $localeSwitcherBlock = new LocaleSwitcherBlockService($environment);

        $blockContext = $this->getBlockContext($localeSwitcherBlock);

        $this->assertSettings([
            'admin' => null,
            'object' => null,
            'template' => '@SonataTranslation/Block/block_locale_switcher.html.twig',
            'locale_switcher_route' => null,
            'locale_switcher_route_parameters' => [],
        ], $blockContext);
    }
}
