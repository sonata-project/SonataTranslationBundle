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

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;

/**
 * @author Alfonso Machado <email@alfonsomachado.com>
 */
final class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    public function alterNewInstance(AdminInterface $admin, object $object): void
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    public function alterObject(AdminInterface $admin, object $object): void
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    public function preUpdate(AdminInterface $admin, object $object): void
    {
        if (!$object instanceof TranslatableInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The object passed to "%s()" method MUST be properly configured using'
                .' "knplabs/doctrine-behaviors" in order to have a "mergeNewTranslations" method.',
                __METHOD__
            ));
        }

        $object->mergeNewTranslations();
    }

    public function prePersist(AdminInterface $admin, object $object): void
    {
        if (!$object instanceof TranslatableInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The object passed to "%s()" method MUST be properly configured using'
                .' "knplabs/doctrine-behaviors" in order to have a "mergeNewTranslations" method.',
                __METHOD__
            ));
        }

        $object->mergeNewTranslations();
    }
}
