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

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
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

    /**
     * @param TranslatableChecker $translatableChecker
     */
    public function setTranslatableChecker($translatableChecker): void
    {
        $this->translatableChecker = $translatableChecker;
    }

    /**
     * @return TranslatableChecker
     */
    public function getTranslatableChecker()
    {
        return $this->translatableChecker;
    }

    /**
     * Return current translatable locale
     * ie: the locale used to load object translations != current request locale.
     *
     * @phpstan-param AdminInterface<object> $admin
     *
     * @return string
     */
    public function getTranslatableLocale(AdminInterface $admin)
    {
        if (null === $this->translatableLocale) {
            if ($admin->hasRequest()) {
                $this->translatableLocale = $admin->getRequest()->get(self::TRANSLATABLE_LOCALE_PARAMETER);
            }
            if (null === $this->translatableLocale) {
                $this->translatableLocale = $this->defaultTranslationLocale;
            }
        }

        return $this->translatableLocale;
    }

    public function getPersistentParameters(AdminInterface $admin): array
    {
        return [self::TRANSLATABLE_LOCALE_PARAMETER => $this->getTranslatableLocale($admin)];
    }

    public function alterNewInstance(AdminInterface $admin, object $object): void
    {
        if (null === $object->getLocale()) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }
}
