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

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Knp\DoctrineBehaviors\DoctrineBehaviorsBundle;
use Sonata\AdminBundle\SonataAdminBundle;
use Sonata\BlockBundle\SonataBlockBundle;
use Sonata\Doctrine\Bridge\Symfony\SonataDoctrineBundle;
use Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle;
use Sonata\Form\Bridge\Symfony\SonataFormBundle;
use Sonata\TranslationBundle\SonataTranslationBundle;
use Sonata\Twig\Bridge\Symfony\SonataTwigBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AuthenticatorFactoryInterface;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\RouteCollectionBuilder;

final class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [
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
            new DoctrineBehaviorsBundle(),
        ];
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

    /**
     * TODO: Use RoutingConfigurator when dropping support of symfony < 5.1.
     *
     * @param RoutingConfigurator|RouteCollectionBuilder $routes
     *
     * @psalm-suppress DeprecatedClass
     */
    protected function configureRoutes($routes): void
    {
        $routes->import(sprintf('%s/config/routes.yaml', $this->getProjectDir()));
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        // NEXT_MAJOR: Remove the next following 2 lines.
        $loader->load(__DIR__.'/config/config_v4.yml');
        $loader->load(__DIR__.'/config/security_v4.yml');

        // NEXT_MAJOR: Uncomment the following lines.
//        if (interface_exists(AuthenticatorFactoryInterface::class)) {
//            $loader->load(__DIR__.'/config/config_v5.yml');
//            $loader->load(__DIR__.'/config/security_v5.yml');
//        } else {
//            $loader->load(__DIR__.'/config/config_v4.yml');
//            $loader->load(__DIR__.'/config/security_v4.yml');
//        }

        $gedmoMappingDir = is_dir(__DIR__.'/../../vendor/gedmo/doctrine-extensions/src/Translatable/Entity')
            ? '%kernel.project_dir%/../../vendor/gedmo/doctrine-extensions/src/Translatable/Entity'
            // NEXT_MAJOR: Remove next line.
            : '%kernel.project_dir%/../../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity';

        $container
            ->loadFromExtension('doctrine', [
                'dbal' => ['url' => '%env(resolve:DATABASE_URL)%'],
                'orm' => [
                    'auto_generate_proxy_classes' => true,
                    'auto_mapping' => true,
                    'mappings' => [
                        'tests' => [
                            'type' => 'annotation',
                            'dir' => '%kernel.project_dir%/Entity',
                            'is_bundle' => false,
                            'prefix' => 'Sonata\TranslationBundle\Tests\App\Entity',
                        ],
                        'gedmo_translatable' => [
                            'type' => 'annotation',
                            'prefix' => 'Gedmo\Translatable\Entity',
                            'dir' => $gedmoMappingDir,
                            'is_bundle' => false,
                        ],
                    ],
                ],
            ]);

        $container
            ->loadFromExtension('sonata_translation', [
                'default_locale' => 'en',
                'locales' => ['en', 'es', 'fr'],
                'locale_switcher_show_country_flags' => false,
                'gedmo' => [
                    'enabled' => true,
                    'translatable_listener_service' => 'app.gedmo.translation_listener',
                ],
                'knplabs' => [
                    'enabled' => true,
                ],
            ]);

        $loader->load(__DIR__.'/config/services.php');
    }

    private function getBaseDir(): string
    {
        return sys_get_temp_dir().'/sonata-translation-bundle/var/';
    }
}
