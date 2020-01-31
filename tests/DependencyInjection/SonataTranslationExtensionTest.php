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
use Sonata\TranslationBundle\DependencyInjection\SonataTranslationExtension;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
final class SonataTranslationExtensionTest extends AbstractExtensionTestCase
{
    /**
     * NEXT_MAJOR: remove this annotation and corresponding deprecation notice.
     *
     * @group legacy
     */
    public function testLoadServiceDefinitionWhenSonataDoctrineORMAdminBundleBundleIsRegistered(): void
    {
        $this->container->setParameter('kernel.bundles', ['SonataDoctrineORMAdminBundle' => 'whatever']);
        $this->load();
        $this->assertContainerBuilderHasService(
            'sonata_translation.checker.translatable',
            'Sonata\TranslationBundle\Checker\TranslatableChecker'
        );
        $this->assertContainerBuilderHasService(
            'sonata_translation.filter.type.translation_field',
            'Sonata\TranslationBundle\Filter\TranslationFieldFilter'
        );
    }

    /**
     * NEXT_MAJOR: remove this annotation and corresponding deprecation notice.
     *
     * @group legacy
     */
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
