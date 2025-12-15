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

namespace Sonata\TranslationBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @phpstan-template T of object
 * @phpstan-extends AbstractAdminExtension<T>
 *
 * @internal
 */
abstract class AbstractTranslatableAdminExtension extends AbstractAdminExtension implements ResetInterface
{
    public const TRANSLATABLE_LOCALE_PARAMETER = 'tl';

    protected ?string $translatableLocale = null;

    public function __construct(
        protected TranslatableChecker $translatableChecker,
        private LocaleProviderInterface $localeProvider,
    ) {
    }

    public function configurePersistentParameters(AdminInterface $admin, array $parameters): array
    {
        $parameters[self::TRANSLATABLE_LOCALE_PARAMETER] = $this->getTranslatableLocale();

        return $parameters;
    }

    public function reset(): void
    {
        $this->translatableLocale = null;
    }

    final protected function getTranslatableChecker(): TranslatableChecker
    {
        return $this->translatableChecker;
    }

    /**
     * Return current translatable locale
     * ie: the locale used to load object translations != current request locale.
     */
    final protected function getTranslatableLocale(): string
    {
        if (null === $this->translatableLocale) {
            $this->translatableLocale = $this->localeProvider->get();
        }

        return $this->translatableLocale;
    }
}
