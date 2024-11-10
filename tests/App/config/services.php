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

use Gedmo\Mapping\Driver\AttributeReader;
use Gedmo\Translatable\TranslatableListener;
use Knp\DoctrineBehaviors\DoctrineBehaviorsBundle;
use Knp\DoctrineBehaviors\EventSubscriber\TranslatableEventSubscriber;
use Knp\DoctrineBehaviors\Provider\UserProvider;
use Sonata\TranslationBundle\Tests\App\Admin\GedmoCategoryAdmin;
use Sonata\TranslationBundle\Tests\App\Admin\KnpCategoryAdmin;
use Sonata\TranslationBundle\Tests\App\Entity\GedmoCategory;
use Sonata\TranslationBundle\Tests\App\KnpEntity\KnpCategory;
use Sonata\TranslationBundle\Tests\App\Provider\DummyUserProvider;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()

        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->load('Sonata\\TranslationBundle\\Tests\\App\\DataFixtures\\', \dirname(__DIR__).'/DataFixtures');

    $services->set(GedmoCategoryAdmin::class)
        ->tag('sonata.admin', [
            'manager_type' => 'orm',
            'model_class' => GedmoCategory::class,
            'label' => 'Gedmo Category',
        ])

    ->set('gedmo.mapping.driver.attribute', AttributeReader::class)

    ->set('app.gedmo.translation_listener', TranslatableListener::class)
        ->call('setAnnotationReader', [service('gedmo.mapping.driver.attribute')])
        ->call('setDefaultLocale', [param('locale')])
        ->call('setTranslationFallback', [false])
        ->tag('doctrine.event_listener', ['event' => 'postLoad'])
        ->tag('doctrine.event_listener', ['event' => 'postPersist'])
        ->tag('doctrine.event_listener', ['event' => 'preFlush'])
        ->tag('doctrine.event_listener', ['event' => 'onFlush'])
        ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata']);

    if (class_exists(DoctrineBehaviorsBundle::class)) {
        $services->set(KnpCategoryAdmin::class)
            ->tag('sonata.admin', [
                'manager_type' => 'orm',
                'model_class' => KnpCategory::class,
                'label' => 'Knp Category',
            ]);

        // Temporary fix to decorate User Provider from KNP (see https://github.com/KnpLabs/DoctrineBehaviors/pull/727)
        $services
            ->set(DummyUserProvider::class)
                ->decorate(UserProvider::class)

            // Temporary fix to use event listeners instead of subscriber (see https://github.com/KnpLabs/DoctrineBehaviors/pull/738)
            ->set('app.knplabs.translation_listener', TranslatableEventSubscriber::class)
            ->arg('$translatableFetchMode', 'LAZY')
            ->arg('$translationFetchMode', 'LAZY')
            ->tag('doctrine.event_listener', ['event' => 'postLoad'])
            ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata'])
            ->tag('doctrine.event_listener', ['event' => 'prePersist']);
    }
};
