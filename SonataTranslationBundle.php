<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle;

use Sonata\TranslationBundle\DependencyInjection\Compiler\AdminExtensionCompilerPass;
use Sonata\TranslationBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class SonataTranslationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GlobalVariablesCompilerPass());
        $container->addCompilerPass(new AdminExtensionCompilerPass());
    }
}
