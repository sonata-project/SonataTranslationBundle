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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sonata\TranslationBundle\Twig\Extension\SonataTranslationExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()

        ->set('sonata_translation.twig.sonata_translation_extension', SonataTranslationExtension::class)
            ->tag('twig.extension')
            ->args([
                service('sonata_translation.checker.translatable'),
            ]);
};
