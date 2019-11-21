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

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class SonataTranslationExtension extends Extension
{
    /**
     * {@inheritdoc}
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

        if ($config['locale_switcher']) {
            $loader->load('service_locale_switcher.xml');
        }

        $bundles = $container->getParameter('kernel.bundles');
        if (\array_key_exists('SonataDoctrineORMAdminBundle', $bundles)) {
            $loader->load('service_orm.xml');
        }

        $translationTargets = [];
        if ($config['gedmo']['enabled']) {
            $isEnabled = true;
            $loader->load('service_gedmo.xml');

            $translationTargets['gedmo']['implements'] = array_merge(
                ['Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface'],
                $config['gedmo']['implements']
            );
            $translationTargets['gedmo']['instanceof'] = $config['gedmo']['instanceof'];
        }
        if ($config['knplabs']['enabled']) {
            $isEnabled = true;
            $loader->load('service_knplabs.xml');

            $translationTargets['knplabs']['implements'] = array_merge(
                ['Sonata\TranslationBundle\Model\TranslatableInterface'],
                $config['knplabs']['implements']
            );
            $translationTargets['knplabs']['instanceof'] = $config['knplabs']['instanceof'];
        }
        if ($config['phpcr']['enabled']) {
            $isEnabled = true;
            $loader->load('service_phpcr.xml');

            $translationTargets['phpcr']['implements'] = array_merge(
                [
                    'Sonata\TranslationBundle\Model\Phpcr\TranslatableInterface',
                    'Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface',
                ],
                $config['phpcr']['implements']
            );
            $translationTargets['phpcr']['instanceof'] = $config['phpcr']['instanceof'];
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
}
