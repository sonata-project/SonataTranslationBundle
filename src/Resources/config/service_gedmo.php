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

use Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()

        ->set('sonata_translation.admin.extension.gedmo_translatable', TranslatableAdminExtension::class)
            ->tag('sonata.admin.extension')
            ->args([
                service('sonata_translation.checker.translatable'),
                service('sonata_translation.listener.translatable'),
                service('doctrine'),
                service('sonata_translation.admin.provider.request_locale_provider'),
            ]);
};
