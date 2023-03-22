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

use Gedmo\Translatable\Translatable as GedmoTranslatable;
use Gedmo\Translatable\TranslatableListener;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KNPTranslatableInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class SonataTranslationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('sonata_translation.locales', $config['locales']);
        $container->setParameter('sonata_translation.default_filter_mode', $config['default_filter_mode']);
        $container->setParameter('sonata_translation.default_locale', $config['default_locale']);

        $isEnabled = false;
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('provider.php');
        $loader->load('twig_intl.php');

        if ($config['locale_switcher']) {
            $loader->load('service_locale_switcher.php');
        }

        $bundles = $container->getParameter('kernel.bundles');
        \assert(\is_array($bundles));
        if (\array_key_exists('SonataDoctrineORMAdminBundle', $bundles)) {
            $loader->load('service_orm.php');
        }

        $translationTargets = [];

        if ($this->isConfigEnabled($container, $config['gedmo'])) {
            $isEnabled = true;

            $this->registerTranslatableListener($container, $config['gedmo']);

            $loader->load('service_gedmo.php');

            /**
             * @phpstan-var list<class-string>
             */
            $listOfInterfaces = array_merge(
                [
                    GedmoTranslatable::class,
                ],
                $config['gedmo']['implements']
            );
            $translationTargets['gedmo']['implements'] = $listOfInterfaces;

            /**
             * @phpstan-var list<class-string>
             */
            $listOfClasses = $config['gedmo']['instanceof'];
            $translationTargets['gedmo']['instanceof'] = $listOfClasses;
        }

        if ($this->isConfigEnabled($container, $config['knplabs'])) {
            $isEnabled = true;
            $loader->load('service_knplabs.php');

            /**
             * @phpstan-var list<class-string>
             */
            $listOfInterfaces = array_merge(
                [
                    KNPTranslatableInterface::class,
                ],
                $config['knplabs']['implements']
            );
            $translationTargets['knplabs']['implements'] = $listOfInterfaces;

            /**
             * @phpstan-var list<class-string>
             */
            $listOfClasses = $config['knplabs']['instanceof'];
            $translationTargets['knplabs']['instanceof'] = $listOfClasses;
        }

        $loader->load('checker.php'); // NEXT_MAJOR: Move this line inside the `if`
        if (true === $isEnabled) {
            $loader->load('block.php');
            $loader->load('listener.php');
            $loader->load('twig.php');
        }

        $container->setParameter('sonata_translation.targets', $translationTargets);

        $this->configureChecker($container, $translationTargets);
    }

    /**
     * @phpstan-param array{
     *  gedmo?: array{implements: list<class-string>, instanceof: list<class-string>},
     *  knplabs?: array{implements: list<class-string>, instanceof: list<class-string>},
     * } $translationTargets
     */
    private function configureChecker(ContainerBuilder $container, array $translationTargets): void
    {
        if (!$container->hasDefinition('sonata_translation.checker.translatable')) {
            return;
        }
        $translatableCheckerDefinition = $container->getDefinition('sonata_translation.checker.translatable');

        $supportedInterfaces = [];
        $supportedModels = [];
        foreach ($translationTargets as $targets) {
            $supportedInterfaces = [...$supportedInterfaces, ...$targets['implements']];
            $supportedModels = [...$supportedModels, ...$targets['instanceof']];
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
            ->addMethodCall('setTranslatableLocale', ['%locale%'])
            ->addMethodCall('setTranslationFallback', [false])
            ->addTag('doctrine.event_subscriber');
    }
}
