<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Checker\TranslatableChecker;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
abstract class AbstractTranslatableAdminExtension extends AdminExtension
{
    /**
     * Request parameter.
     */
    const TRANSLATABLE_LOCALE_PARAMETER = 'tl';

    /**
     * @var string
     */
    protected $translatableLocale;

    /**
     * @var TranslatableChecker
     */
    protected $translatableChecker;

    /**
     * @param TranslatableChecker $translatableChecker
     */
    public function __construct(TranslatableChecker $translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    /**
     * @param TranslatableChecker $translatableChecker
     */
    public function setTranslatableChecker($translatableChecker)
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
     * @return string
     */
    public function getTranslatableLocale(AdminInterface $admin)
    {
        if ($this->translatableLocale == null) {
            if ($admin->getRequest()) {
                $this->translatableLocale = $admin->getRequest()->get(self::TRANSLATABLE_LOCALE_PARAMETER);
            }
            if ($this->translatableLocale == null) {
                $this->translatableLocale = $this->getDefaultTranslationLocale($admin);
            }
        }

        return $this->translatableLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters(AdminInterface $admin)
    {
        return array(self::TRANSLATABLE_LOCALE_PARAMETER => $this->getTranslatableLocale($admin));
    }

    /**
     * {@inheritdoc}
     */
    public function alterNewInstance(AdminInterface $admin, $object)
    {
        if ($object->getLocale() === null) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(AdminInterface $admin)
    {
        return $admin->getConfigurationPool()->getContainer();
    }

    /**
     * Return the list of possible locales for your models.
     *
     * @return array
     */
    protected function getTranslationLocales(AdminInterface $admin)
    {
        return $this->getContainer($admin)->getParameter('sonata_translation.locales');
    }

    /**
     * Return the default locale if url parameter is not present.
     *
     * @return string
     */
    protected function getDefaultTranslationLocale(AdminInterface $admin)
    {
        return $this->getContainer($admin)->getParameter('sonata_translation.default_locale');
    }
}
