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

namespace Sonata\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @internal
 */
final class AdminExtensionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $translationTargets = $container->getParameter('sonata_translation.targets');
        \assert(\is_array($translationTargets));
        $adminExtensionReferences = $this->getAdminExtensionReferenceByTypes(array_keys($translationTargets));

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $attributes) {
            $admin = $container->getDefinition($id);
            $modelClass = $container->getParameterBag()->resolveValue($admin->getArgument(1));
            if (!$modelClass || !class_exists($modelClass)) {
                continue;
            }
            $modelClassReflection = new \ReflectionClass($modelClass);

            foreach ($adminExtensionReferences as $type => $reference) {
                foreach ($translationTargets[$type]['implements'] as $interface) {
                    if ($modelClassReflection->implementsInterface($interface)) {
                        $admin->addMethodCall('addExtension', [$reference]);
                    }
                }
                foreach ($translationTargets[$type]['instanceof'] as $class) {
                    if ($modelClassReflection->getName() === $class || $modelClassReflection->isSubclassOf($class)) {
                        $admin->addMethodCall('addExtension', [$reference]);
                    }
                }
            }
        }
    }

    /**
     * @param list<int|string> $types
     *
     * @return Reference[]
     */
    private function getAdminExtensionReferenceByTypes(array $types): array
    {
        $references = [];
        foreach ($types as $type) {
            $references[$type] = new Reference('sonata_translation.admin.extension.'.$type.'_translatable');
        }

        return $references;
    }
}
