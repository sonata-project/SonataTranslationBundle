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
use Sonata\TranslationBundle\Model\TranslatableInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @phpstan-extends AbstractAdminExtension<TranslatableInterface>
 *
 * @internal
 */
abstract class AbstractTranslatableAdminExtension extends AbstractAdminExtension
{
    /**
     * Request parameter.
     */
    public const TRANSLATABLE_LOCALE_PARAMETER = 'tl';

    /**
     * @var string|null
     */
    protected $translatableLocale;

    /**
     * @var TranslatableChecker
     */
    protected $translatableChecker;

    /**
     * @var string
     */
    private $defaultTranslationLocale;

    public function __construct(TranslatableChecker $translatableChecker, string $defaultTranslationLocale)
    {
        $this->translatableChecker = $translatableChecker;
        $this->defaultTranslationLocale = $defaultTranslationLocale;
    }

    public function setTranslatableChecker(TranslatableChecker $translatableChecker): void
    {
        $this->translatableChecker = $translatableChecker;
    }

    public function getTranslatableChecker(): TranslatableChecker
    {
        return $this->translatableChecker;
    }

    /**
     * Return current translatable locale
     * ie: the locale used to load object translations != current request locale.
     *
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     */
    public function getTranslatableLocale(AdminInterface $admin): string
    {
        if (null === $this->translatableLocale) {
            $this->translatableLocale = $this->getLocaleFromAdmin($admin);
        }

        return $this->translatableLocale;
    }

    public function configurePersistentParameters(AdminInterface $admin, array $parameters): array
    {
        $parameters[self::TRANSLATABLE_LOCALE_PARAMETER] = $this->getTranslatableLocale($admin);

        return $parameters;
    }

    public function alterNewInstance(AdminInterface $admin, object $object): void
    {
        if (null === $object->getLocale()) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    /**
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     */
    private function getLocaleFromAdmin(AdminInterface $admin): string
    {
        if (!$admin->hasRequest()) {
            return $this->defaultTranslationLocale;
        }

        $locale = (string) $admin->getRequest()->get(self::TRANSLATABLE_LOCALE_PARAMETER);

        if ('' !== $locale) {
            return $locale;
        }

        return $this->defaultTranslationLocale;
    }
}
