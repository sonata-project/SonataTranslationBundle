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

use Sonata\TranslationBundle\Enum\TranslationFilterMode;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress PossiblyNullReference, UndefinedInterfaceMethod
     *
     * @see https://github.com/psalm/psalm-plugin-symfony/issues/174
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_translation');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->info('The list of your frontend locales in which your models will be translatable.')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('default_locale')
                    ->defaultValue('en')
                    ->info('The frontend locale that is used by default.')
                ->end()
                ->enumNode('default_filter_mode')
                    ->values(TranslationFilterMode::AVAILABLE_FILTER_TYPES)
                    ->defaultValue(TranslationFilterMode::GEDMO)
                    ->info('The filter mode that is used by default.')
                ->end()
                ->arrayNode('gedmo')
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('implements')
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('instanceof')
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')->end()
                        ->end()
                        ->scalarNode('translatable_listener_service')
                            ->info('Custom translatable listener service name when using gedmo/doctrine-extensions')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('knplabs')
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('implements')
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('instanceof')
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->booleanNode('locale_switcher')
                    ->info('Enable the global locale switcher services.')
                    ->defaultFalse()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
