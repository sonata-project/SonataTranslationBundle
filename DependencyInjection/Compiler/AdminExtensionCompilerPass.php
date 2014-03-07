<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonata\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class AdminExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $translationInterfaces = $container->getParameter('sonata_translation.interfaces');

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $attributes) {
            $admin = $container->getDefinition($id);
            $modelClass = $container->getParameterBag()->resolveValue($admin->getArgument(1));
            $modelClassReflection = new \ReflectionClass($modelClass);

            if (isset($translationInterfaces['gedmo'])) {
                $adminExtensionReference = new Reference('sonata_translation.admin.extension.gedmo_translatable');
                foreach ($translationInterfaces['gedmo'] as $interface) {
                    if ($modelClassReflection->implementsInterface($interface)) {
                        $admin->addMethodCall('addExtension', array($adminExtensionReference));
                    }
                }
            }

            if (isset($translationInterfaces['phpcr'])) {
                $adminExtensionReference = new Reference('sonata_translation.admin.extension.phpcr_translatable');
                foreach ($translationInterfaces['phpcr'] as $interface) {
                    if ($modelClassReflection->implementsInterface($interface)) {
                        $admin->addMethodCall('addExtension', array($adminExtensionReference));
                    }
                }
            }
        }
    }
}
