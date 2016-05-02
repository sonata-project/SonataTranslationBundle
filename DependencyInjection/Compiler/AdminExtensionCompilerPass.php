<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class AdminExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $translationTargets = $container->getParameter('sonata_translation.targets');
        $adminExtensionReferences = $this->getAdminExtensionReferenceByTypes(array_keys($translationTargets));

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $attributes) {
            $admin = $container->getDefinition($id);
            $modelClass = $container->getParameterBag()->resolveValue($admin->getArgument(1));
            if (!class_exists($modelClass)) {
                continue;
            }
            $modelClassReflection = new \ReflectionClass($modelClass);

            foreach ($adminExtensionReferences as $type => $reference) {
                foreach ($translationTargets[$type]['implements'] as $interface) {
                    if ($modelClassReflection->implementsInterface($interface)) {
                        $admin->addMethodCall('addExtension', array($reference));
                    }
                }
                foreach ($translationTargets[$type]['instanceof'] as $class) {
                    if ($modelClassReflection->getName() == $class || $modelClassReflection->isSubclassOf($class)) {
                        $admin->addMethodCall('addExtension', array($reference));
                    }
                }
            }
        }
    }

    /**
     * @param array $types
     *
     * @return Reference[]
     */
    protected function getAdminExtensionReferenceByTypes(array $types)
    {
        $references = array();
        foreach ($types as $type) {
            $references[$type] = new Reference('sonata_translation.admin.extension.'.$type.'_translatable');
        }

        return $references;
    }
}
