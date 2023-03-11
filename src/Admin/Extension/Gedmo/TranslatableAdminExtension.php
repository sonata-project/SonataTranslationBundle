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

namespace Sonata\TranslationBundle\Admin\Extension\Gedmo;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Gedmo\Translatable\Translatable;
use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @phpstan-extends AbstractTranslatableAdminExtension<Translatable>
 *
 * @internal
 */
final class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    public function __construct(
        TranslatableChecker $translatableChecker,
        private TranslatableListener $translatableListener,
        private ManagerRegistry $managerRegistry,
        LocaleProviderInterface $localeProvider
    ) {
        parent::__construct($translatableChecker, $localeProvider);
    }

    public function alterNewInstance(AdminInterface $admin, object $object): void
    {
        if (!$this->getTranslatableChecker()->isTranslatable($object)) {
            return;
        }

        $this->setLocale($object);
    }

    public function alterObject(AdminInterface $admin, object $object): void
    {
        if (!$this->getTranslatableChecker()->isTranslatable($object)) {
            return;
        }

        $this->setLocale($object);
    }

    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query): void
    {
        $this->translatableListener->setTranslatableLocale($this->getTranslatableLocale());
        $this->translatableListener->setTranslationFallback(false);
    }

    private function setLocale(object $object): void
    {
        $translatableLocale = $this->getTranslatableLocale();
        $this->translatableListener->setTranslatableLocale($translatableLocale);
        $this->translatableListener->setTranslationFallback(false);

        $objectClassName = ClassUtils::getClass($object);

        $objectManager = $this->managerRegistry->getManagerForClass($objectClassName);

        \assert($objectManager instanceof ObjectManager);

        $configuration = $this->translatableListener->getConfiguration($objectManager, $objectClassName);
        if (!isset($configuration['locale'])) {
            throw new \LogicException(sprintf(
                'There is no locale or language property found on class: "%s"',
                $object::class
            ));
        }

        $reflClass = $objectManager->getClassMetadata($objectClassName)->getReflectionClass();

        $reflectionProperty = $reflClass->getProperty($configuration['locale']);
        $reflectionProperty->setAccessible(true);

        if ($reflectionProperty->getValue($object) === $translatableLocale) {
            return;
        }

        $reflectionProperty->setValue($object, $translatableLocale);

        if ($objectManager->contains($object)) {
            $objectManager->refresh($object);
        }
    }
}
