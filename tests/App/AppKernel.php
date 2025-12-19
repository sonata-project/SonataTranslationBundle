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

namespace Sonata\TranslationBundle\Tests\App;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\CacheCompatibilityPass;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Knp\DoctrineBehaviors\DoctrineBehaviorsBundle;
use Sonata\AdminBundle\SonataAdminBundle;
use Sonata\BlockBundle\Cache\HttpCacheHandler;
use Sonata\BlockBundle\SonataBlockBundle;
use Sonata\Doctrine\Bridge\Symfony\SonataDoctrineBundle;
use Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle;
use Sonata\Form\Bridge\Symfony\SonataFormBundle;
use Sonata\TranslationBundle\SonataTranslationBundle;
use Sonata\Twig\Bridge\Symfony\SonataTwigBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\StimulusBundle\StimulusBundle;

final class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        $bundles = [
            new FrameworkBundle(),
            new KnpMenuBundle(),
            new SecurityBundle(),
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),
            new SonataAdminBundle(),
            new SonataDoctrineORMAdminBundle(),
            new SonataBlockBundle(),
            new SonataDoctrineBundle(),
            new SonataFormBundle(),
            new SonataTwigBundle(),
            new SonataTranslationBundle(),
            new TwigBundle(),
            new StimulusBundle(),
        ];

        if (class_exists(DoctrineBehaviorsBundle::class)) {
            $bundles[] = new DoctrineBehaviorsBundle();
        }

        return $bundles;
    }

    public function getCacheDir(): string
    {
        return $this->getBaseDir().'cache';
    }

    public function getLogDir(): string
    {
        return $this->getBaseDir().'log';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(\sprintf('%s/config/routes.yaml', $this->getProjectDir()));
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('app.base_dir', $this->getBaseDir());

        $loader->load(__DIR__.'/config/config.yaml');

        if (class_exists(CacheCompatibilityPass::class)) {
            // doctrine-bundle v2
            $container->loadFromExtension('doctrine', [
                'dbal' => [
                    'use_savepoints' => true,
                ],
                'orm' => [
                    'enable_lazy_ghost_objects' => true,
                ],
            ]);
        }

        if (class_exists(HttpCacheHandler::class)) {
            $loader->load(__DIR__.'/config/config_sonata_block_v4.yaml');
        }

        $mappings = [
            'tests' => [
                'type' => 'attribute',
                'dir' => '%kernel.project_dir%/Entity',
                'is_bundle' => false,
                'prefix' => 'Sonata\TranslationBundle\Tests\App\Entity',
            ],
            'gedmo_translatable' => [
                'type' => 'attribute',
                'prefix' => 'Gedmo\Translatable\Entity',
                'dir' => '%kernel.project_dir%/../../vendor/gedmo/doctrine-extensions/src/Translatable/Entity',
                'is_bundle' => false,
            ],
        ];

        if (class_exists(DoctrineBehaviorsBundle::class)) {
            $mappings['knp_translatable'] = [
                'type' => 'attribute',
                'prefix' => 'Sonata\TranslationBundle\Tests\App\KnpEntity',
                'dir' => '%kernel.project_dir%/KnpEntity',
                'is_bundle' => false,
            ];
        }

        $container
            ->loadFromExtension('doctrine', [
                'dbal' => ['url' => '%env(resolve:DATABASE_URL)%'],
                'orm' => [
                    'auto_mapping' => true,
                    'mappings' => $mappings,
                ],
            ]);

        $extensionsConfig = [
            'gedmo' => [
                'enabled' => true,
                'translatable_listener_service' => 'app.gedmo.translation_listener',
            ],
        ];

        if (class_exists(DoctrineBehaviorsBundle::class)) {
            $extensionsConfig['knplabs'] = [
                'enabled' => true,
            ];
        }

        $container
            ->loadFromExtension('sonata_translation', [
                'default_locale' => 'en',
                'locales' => ['en', 'es', 'fr'],
            ] + $extensionsConfig);

        $loader->load(__DIR__.'/config/services.php');
    }

    private function getBaseDir(): string
    {
        return sys_get_temp_dir().'/sonata-translation-bundle/var/';
    }
}
