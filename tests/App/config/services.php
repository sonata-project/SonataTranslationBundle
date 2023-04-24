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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Translatable\TranslatableListener;
use Sonata\TranslationBundle\Tests\App\Admin\GedmoCategoryAdmin;
use Sonata\TranslationBundle\Tests\App\Admin\KnpCategoryAdmin;
use Sonata\TranslationBundle\Tests\App\Entity\GedmoCategory;
use Sonata\TranslationBundle\Tests\App\Entity\KnpCategory;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()

        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->load('Sonata\\TranslationBundle\\Tests\\App\\DataFixtures\\', \dirname(__DIR__).'/DataFixtures')

        ->set(GedmoCategoryAdmin::class)
            ->tag('sonata.admin', [
                'manager_type' => 'orm',
                'model_class' => GedmoCategory::class,
                'label' => 'Gedmo Category',
            ])

        ->set(KnpCategoryAdmin::class)
            ->tag('sonata.admin', [
                'manager_type' => 'orm',
                'model_class' => KnpCategory::class,
                'label' => 'Knp Category',
            ])

        ->set('app.gedmo.translation_listener', TranslatableListener::class)
            ->call('setAnnotationReader', [service('annotation_reader')])
            ->call('setDefaultLocale', [param('locale')])
            ->call('setTranslationFallback', [false])
            ->tag('doctrine.event_subscriber');
};
