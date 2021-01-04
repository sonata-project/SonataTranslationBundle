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

namespace Sonata\TranslationBundle\Admin\Extension\Knplabs;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;

/**
 * @final since sonata-project/translation-bundle 2.7
 *
 * @author Alfonso Machado <email@alfonsomachado.com>
 */
class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    public function alterNewInstance(AdminInterface $admin, $object)
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    public function alterObject(AdminInterface $admin, $object)
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    public function preUpdate(AdminInterface $admin, $object)
    {
        if (!\is_callable([$object, 'mergeNewTranslations'])) {
            throw new \InvalidArgumentException(sprintf(
                'The object passed to "%s()" method MUST be properly configured using'
                .' "knplabs/doctrine-behaviors" in order to have a "mergeNewTranslations" method.',
                __METHOD__
            ));
        }

        $object->mergeNewTranslations();
    }

    public function prePersist(AdminInterface $admin, $object)
    {
        if (!\is_callable([$object, 'mergeNewTranslations'])) {
            throw new \InvalidArgumentException(sprintf(
                'The object passed to "%s()" method MUST be properly configured using'
                .' "knplabs/doctrine-behaviors" in order to have a "mergeNewTranslations" method.',
                __METHOD__
            ));
        }

        $object->mergeNewTranslations();
    }
}
