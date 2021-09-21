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

use Gedmo\Translatable\TranslatableListener;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KnpTranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface as GedmoTranslatableInterfaceAlias;
use Sonata\TranslationBundle\Tests\App\Admin\GedmoCategoryAdmin;
use Sonata\TranslationBundle\Tests\App\Admin\KnpCategoryAdmin;
use Sonata\TranslationBundle\Tests\App\Entity\GedmoCategory;
use Sonata\TranslationBundle\Tests\App\Entity\KnpCategory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $containerConfigurator): void {
    // NEXT_MAJOR: Remove this parameter.
    $containerConfigurator->parameters()
        ->set('sonata_translation.targets', [
            'gedmo' => [
                'implements' => [GedmoTranslatableInterfaceAlias::class],
                'instanceof' => [],
            ],
            'knplabs' => [
                'implements' => [KnpTranslatableInterface::class],
                'instanceof' => [],
            ],
        ]);

    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->load('Sonata\\TranslationBundle\\Tests\\App\\DataFixtures\\', dirname(__DIR__).'/DataFixtures')

        ->set(GedmoCategoryAdmin::class)
            ->tag('sonata.admin', [
                'manager_type' => 'orm',
                'label' => 'Gedmo Category',
            ])
            ->args([
                '',
                GedmoCategory::class,
                null,
            ])

        ->set('app.gedmo.translation_listener', TranslatableListener::class)
            ->call('setAnnotationReader', [new Reference('annotation_reader')])
            ->call('setDefaultLocale', ['%locale%'])
            ->call('setTranslationFallback', [false])
            ->tag('doctrine.event_subscriber')

        ->set(KnpCategoryAdmin::class)
        ->tag('sonata.admin', [
            'manager_type' => 'orm',
            'label' => 'Knp Category',
        ])
        ->args([
            '',
            KnpCategory::class,
            null,
        ]);
};
