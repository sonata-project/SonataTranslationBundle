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

namespace Sonata\TranslationBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\DependencyInjection\SonataTranslationExtension;
use Sonata\TranslationBundle\Filter\TranslationFieldFilter;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
final class SonataTranslationExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadTwigIntlExtension(): void
    {
        $this->container->setParameter('kernel.bundles', []);
        $this->load();

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'sonata_translation.twig.intl_extension',
            'twig.extension'
        );
    }

    public function testLoadServiceDefinitionWhenSonataDoctrineORMAdminBundleBundleIsRegistered(): void
    {
        $this->container->setParameter('kernel.bundles', ['SonataDoctrineORMAdminBundle' => 'whatever']);
        $this->load();
        $this->assertContainerBuilderHasService(
            'sonata_translation.checker.translatable',
            TranslatableChecker::class
        );
        $this->assertContainerBuilderHasService(
            'sonata_translation.filter.type.translation_field',
            TranslationFieldFilter::class
        );
    }

    public function testLoadServiceDefinitionNoCheckerTranslatable(): void
    {
        $this->container->setParameter('kernel.bundles', []);
        $this->load();

        $this->assertContainerBuilderNotHasService('sonata_translation.checker.translatable');
    }

    protected function getContainerExtensions(): array
    {
        return [new SonataTranslationExtension()];
    }
}
