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
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Twig\Environment;

final class LocaleSwitcherBlockServiceTest extends BlockServiceTestCase
{
    use ExpectDeprecationTrait;

    public function testDefaultSettings(): void
    {
        $environment = $this->createStub(Environment::class);

        $showCountryFlags = true;
        $localeSwitcherBlock = new LocaleSwitcherBlockService($environment, $showCountryFlags);

        $blockContext = $this->getBlockContext($localeSwitcherBlock);

        $this->assertSettings([
            'admin' => null,
            'object' => null,
            'template' => '@SonataTranslation/Block/block_locale_switcher.html.twig',
            'locale_switcher_route' => null,
            'locale_switcher_route_parameters' => [],
            'locale_switcher_show_country_flags' => $showCountryFlags,
        ], $blockContext);
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @group legacy
     */
    public function testDefaultSettingsWithWrongConstructor(): void
    {
        $this->expectException(\TypeError::class);
        new LocaleSwitcherBlockService($this->createStub(Environment::class), new \stdClass());
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @group legacy
     */
    public function testDefaultSettingsWithDeprecatedConstructor(): void
    {
        $environment = $this->createStub(Environment::class);

        $showCountryFlags = true;
        $this->expectDeprecation(
            'Passing "null" as argument 2 to "Sonata\TranslationBundle\Block\LocaleSwitcherBlockService::__construct()"'
            .' is deprecated since sonata-project/translation-bundle 2.7 and will throw a "TypeError" error in version 3.0.'
            .' You must pass a "bool" value instead.'
        );
        $localeSwitcherBlock = new LocaleSwitcherBlockService($environment, null, $showCountryFlags);

        $blockContext = $this->getBlockContext($localeSwitcherBlock);

        $this->assertSettings([
            'admin' => null,
            'object' => null,
            'template' => '@SonataTranslation/Block/block_locale_switcher.html.twig',
            'locale_switcher_route' => null,
            'locale_switcher_route_parameters' => [],
            'locale_switcher_show_country_flags' => $showCountryFlags,
        ], $blockContext);
    }
}
