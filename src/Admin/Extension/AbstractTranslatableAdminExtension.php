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
use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * @var string|null
     */
    private $defaultTranslationLocale;

    /**
     * NEXT_MAJOR: Make $defaultTranslationLocale mandatory.
     */
    public function __construct(TranslatableChecker $translatableChecker, ?string $defaultTranslationLocale = null)
    {
        $this->translatableChecker = $translatableChecker;

        // NEXT_MAJOR: Remove this block.
        if (null === $defaultTranslationLocale) {
            @trigger_error(sprintf(
                'Omitting the argument 2 or passing other type than "string" to "%s()" is deprecated'
                .' since sonata-project/translation-bundle 2.x and will be not possible in version 3.0.',
                __METHOD__
            ), E_USER_DEPRECATED);
        }

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
                // NEXT_MAJOR: Remove the call to $this->getDefaultTranslationLocale($admin).
                $this->translatableLocale = $this->defaultTranslationLocale ?? $this->getDefaultTranslationLocale($admin);
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

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since version 2.x, to be removed in 3.0. Use dependency injection instead.
     *
     * @phpstan-param AdminInterface<object> $admin
     *
     * @return ContainerInterface
     */
    protected function getContainer(AdminInterface $admin)
    {
        @trigger_error(sprintf(
            'The "%s()" method is deprecated since sonata-project/translation-bundle 2.x and will be removed in 3.0.'
            .' Use dependency injection instead.',
            __METHOD__
        ), E_USER_DEPRECATED);

        \assert(\is_callable([$admin, 'getConfigurationPool']));

        return $admin->getConfigurationPool()->getContainer();
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since version 2.x, to be removed in 3.0.
     *
     * @phpstan-param AdminInterface<object> $admin
     *
     * Return the list of possible locales for your models.
     *
     * @return string[]
     */
    protected function getTranslationLocales(AdminInterface $admin)
    {
        @trigger_error(sprintf(
            'The "%s()" method is deprecated since sonata-project/translation-bundle 2.x and will be removed in 3.0.',
            __METHOD__
        ), E_USER_DEPRECATED);

        return $this->getContainer($admin)->getParameter('sonata_translation.locales');
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since version 2.x, to be removed in 3.0.
     *
     * Return the default locale if url parameter is not present.
     *
     * @phpstan-param AdminInterface<object> $admin
     *
     * @return string
     */
    protected function getDefaultTranslationLocale(AdminInterface $admin)
    {
        return $this->getContainer($admin)->getParameter('sonata_translation.default_locale');
    }
}
