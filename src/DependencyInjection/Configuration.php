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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sonata_translation');

        // Keep compatibility with symfony/config < 4.2
        if (!\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->root('sonata_translation');
        } else {
            $rootNode = $treeBuilder->getRootNode();
        }

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
                ->arrayNode('phpcr')
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
