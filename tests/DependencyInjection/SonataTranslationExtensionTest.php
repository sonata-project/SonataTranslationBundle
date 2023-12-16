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

use Gedmo\Translatable\TranslatableListener;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sonata\TranslationBundle\DependencyInjection\SonataTranslationExtension;
use Sonata\TranslationBundle\Filter\TranslationFieldFilter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

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
            'sonata_translation.filter.type.translation_field',
            TranslationFieldFilter::class
        );
    }

    public function testLoadServiceDefinitionCheckerTranslatable(): void
    {
        $this->container->setParameter('kernel.bundles', []);
        $this->load();

        $this->assertContainerBuilderHasService('sonata_translation.checker.translatable');
    }

    public function testCreatesAnAliasWhenUsingGedmo(): void
    {
        $this->container->setParameter('kernel.bundles', []);
        $this->load([
            'gedmo' => [
                'enabled' => true,
                'translatable_listener_service' => 'stof_doctrine_extensions.listener.translatable',
            ],
        ]);
        $this->assertContainerBuilderHasAlias(
            'sonata_translation.listener.translatable',
            'stof_doctrine_extensions.listener.translatable'
        );
    }

    public function testRegistersTranslatableListenerWhenUsingGedmo(): void
    {
        $this->container->setParameter('kernel.bundles', []);
        $this->load([
            'gedmo' => [
                'enabled' => true,
            ],
        ]);

        $this->assertContainerBuilderHasService(
            'sonata_translation.listener.translatable',
            TranslatableListener::class
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sonata_translation.listener.translatable',
            'setAnnotationReader',
            [new Reference('annotation_reader', ContainerInterface::IGNORE_ON_INVALID_REFERENCE)]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sonata_translation.listener.translatable',
            'setTranslatableLocale',
            ['%locale%']
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new SonataTranslationExtension()];
    }
}
