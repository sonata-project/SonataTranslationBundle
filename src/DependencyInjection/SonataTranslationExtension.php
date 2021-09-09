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

namespace Sonata\TranslationBundle\DependencyInjection;

use Gedmo\Translatable\TranslatableListener;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KNPTranslatableInterface;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface as GedmoTranslatableInterface;
use Sonata\TranslationBundle\Model\Phpcr\TranslatableInterface as PHPCRTranslatableInterface;
use Sonata\TranslationBundle\Model\TranslatableInterface as SonataTranslatableInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @final since sonata-project/translation-bundle 2.7
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class SonataTranslationExtension extends Extension
{
    /**
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('sonata_translation.locales', $config['locales']);
        $container->setParameter('sonata_translation.default_filter_mode', $config['default_filter_mode']);
        $container->setParameter('sonata_translation.default_locale', $config['default_locale']);
        $container->setParameter('sonata_translation.locale_switcher_show_country_flags', $config['locale_switcher_show_country_flags']);

        $isEnabled = false;
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('twig_intl.xml');

        if ($config['locale_switcher']) {
            $loader->load('service_locale_switcher.xml');
        }

        $bundles = $container->getParameter('kernel.bundles');
        \assert(\is_array($bundles));
        if (\array_key_exists('SonataDoctrineORMAdminBundle', $bundles)) {
            $loader->load('service_orm.xml');
        }

        $translationTargets = [];

        if ($config['gedmo']['enabled']) {
            $isEnabled = true;

            $this->registerTranslatableListener($container, $config['gedmo']);

            $loader->load('service_gedmo.xml');

            /**
             * @phpstan-var list<class-string>
             */
            $listOfInterfaces = array_merge(
                [GedmoTranslatableInterface::class],
                $config['gedmo']['implements']
            );
            $translationTargets['gedmo']['implements'] = $listOfInterfaces;

            /**
             * @phpstan-var list<class-string>
             */
            $listOfClasses = $config['gedmo']['instanceof'];
            $translationTargets['gedmo']['instanceof'] = $listOfClasses;
        }
        if ($config['knplabs']['enabled']) {
            $isEnabled = true;
            $loader->load('service_knplabs.xml');

            // NEXT_MAJOR: Replace by KNPTranslatableInterface
            $knpInterfaces = [SonataTranslatableInterface::class];

            // NEXT_MAJOR: Remove this block.
            if (interface_exists(KNPTranslatableInterface::class)) {
                $knpInterfaces[] = KNPTranslatableInterface::class;
            }

            /**
             * @phpstan-var list<class-string>
             */
            $listOfInterfaces = array_merge(
                $knpInterfaces,
                $config['knplabs']['implements']
            );
            $translationTargets['knplabs']['implements'] = $listOfInterfaces;

            /**
             * @phpstan-var list<class-string>
             */
            $listOfClasses = $config['knplabs']['instanceof'];
            $translationTargets['knplabs']['instanceof'] = $listOfClasses;
        }
        if ($config['phpcr']['enabled']) {
            $isEnabled = true;
            $loader->load('service_phpcr.xml');

            /**
             * @phpstan-var list<class-string>
             */
            $listOfInterfaces = array_merge(
                [
                    PHPCRTranslatableInterface::class,
                    'Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface',
                ],
                $config['phpcr']['implements']
            );
            $translationTargets['phpcr']['implements'] = $listOfInterfaces;

            /**
             * @phpstan-var list<class-string>
             */
            $listOfClasses = $config['phpcr']['instanceof'];
            $translationTargets['phpcr']['instanceof'] = $listOfClasses;
        }

        if (true === $isEnabled) {
            $loader->load('block.xml');
            $loader->load('listener.xml');
            $loader->load('twig.xml');
        }

        $container->setParameter('sonata_translation.targets', $translationTargets);

        $this->configureChecker($container, $translationTargets);
    }

    /**
     * @param array $translationTargets
     *
     * @phpstan-param array{
     *  gedmo?: array{implements: list<class-string>, instanceof: list<class-string>},
     *  knplabs?: array{implements: list<class-string>, instanceof: list<class-string>},
     *  phpcr?: array{implements: list<class-string>, instanceof: list<class-string>}
     * } $translationTargets
     *
     * @return void
     */
    protected function configureChecker(ContainerBuilder $container, $translationTargets)
    {
        if (!$container->hasDefinition('sonata_translation.checker.translatable')) {
            return;
        }
        $translatableCheckerDefinition = $container->getDefinition('sonata_translation.checker.translatable');

        $supportedInterfaces = [];
        $supportedModels = [];
        foreach ($translationTargets as $targets) {
            $supportedInterfaces = array_merge($supportedInterfaces, $targets['implements']);
            $supportedModels = array_merge($supportedModels, $targets['instanceof']);
        }

        $translatableCheckerDefinition->addMethodCall('setSupportedInterfaces', [$supportedInterfaces]);
        $translatableCheckerDefinition->addMethodCall('setSupportedModels', [$supportedModels]);
    }

    /**
     * @param array{enabled: bool, translatable_listener_service?: string, implements: list<class-string>, instanceof: list<class-string>} $gedmoConfig
     */
    private function registerTranslatableListener(ContainerBuilder $container, array $gedmoConfig): void
    {
        if (isset($gedmoConfig['translatable_listener_service'])) {
            $container->setAlias(
                'sonata_translation.listener.translatable',
                $gedmoConfig['translatable_listener_service']
            );

            return;
        }

        // Registration based on the documentation
        // see https://github.com/doctrine-extensions/DoctrineExtensions/blob/7c0d5aeab0f840d2a18a18c3dc10b0117c597a42/doc/symfony4.md#doctrine-extension-listener-services
        $container->register('sonata_translation.listener.translatable', TranslatableListener::class)
            ->addMethodCall('setAnnotationReader', [new Reference('annotation_reader')])
            ->addMethodCall('setDefaultLocale', ['%locale%'])
            ->addMethodCall('setTranslationFallback', [false])
            ->addTag('doctrine.event_subscriber');
    }
}
