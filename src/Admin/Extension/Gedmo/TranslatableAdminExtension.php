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
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;

/**
 * @final since sonata-project/translation-bundle 2.7
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    /**
     * @var TranslatableListener|null
     */
    protected $translatableListener;

    /**
     * @var ManagerRegistry|null
     */
    private $managerRegistry;

    /**
     * NEXT_MAJOR: Make $translatableListener and $managerRegistry mandatory, change
     * $defaultTranslationLocaleOrLocaleProvider to LocaleProviderInterface $localeOrLocaleProvider.
     *
     * @param string|LocaleProviderInterface|null $defaultTranslationLocaleOrLocaleProvider
     */
    public function __construct(
        TranslatableChecker $translatableChecker,
        ?TranslatableListener $translatableListener = null,
        ?ManagerRegistry $managerRegistry = null,
        $defaultTranslationLocaleOrLocaleProvider = null
    ) {
        parent::__construct($translatableChecker, $defaultTranslationLocaleOrLocaleProvider);

        if (null === $translatableListener) {
            @trigger_error(sprintf(
                'Not passing an instance of "%s" as argument 2 to "%s()" is deprecated'
                .' since sonata-project/translation-bundle 2.7 and will be mandatory in 3.0.',
                TranslatableListener::class,
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        $this->translatableListener = $translatableListener;

        if (null === $managerRegistry) {
            @trigger_error(sprintf(
                'Not passing an instance of "%s" as argument 3 to "%s()" is deprecated'
                .' since sonata-project/translation-bundle 2.7 and will be mandatory in 3.0.',
                ManagerRegistry::class,
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        $this->managerRegistry = $managerRegistry;
    }

    public function alterNewInstance(AdminInterface $admin, $object)
    {
        // NEXT_MAJOR: Remove the entire "if" block.
        if ($object instanceof TranslatableInterface) {
            @trigger_error(sprintf(
                'Implementing "%s" for entities using gedmo/doctrine-extensions is deprecated'
                .' since sonata-project/translation-bundle 2.10 and will not work in 3.0. You MUST implement "%s"'
                .' instead.',
                TranslatableInterface::class,
                Translatable::class,
            ), \E_USER_DEPRECATED);

            if (null === $object->getLocale()) {
                $object->setLocale($this->getTranslatableLocale($admin));
            }

            return;
        }

        if (!$this->getTranslatableChecker()->isTranslatable($object)) {
            return;
        }

        $this->setLocale($object, $admin);
    }

    public function alterObject(AdminInterface $admin, $object)
    {
        if (!$this->getTranslatableChecker()->isTranslatable($object)) {
            return;
        }

        // NEXT_MAJOR: Use $this->managerRegistry directly.
        $objectManager = $this->getManagerRegistry($admin)->getManagerForClass(\get_class($object));

        \assert($objectManager instanceof ObjectManager);

        // NEXT_MAJOR: Remove the entire "if" block.
        if ($object instanceof TranslatableInterface) {
            @trigger_error(sprintf(
                'Implementing "%s" for entities using gedmo/doctrine-extensions is deprecated'
                .' since sonata-project/translation-bundle 2.10 and will not work in 3.0. You MUST implement "%s"'
                .' instead.',
                TranslatableInterface::class,
                Translatable::class,
            ), \E_USER_DEPRECATED);

            $translatableListener = $this->getTranslatableListener($admin);
            $translatableListener->setTranslatableLocale($this->getTranslatableLocale($admin));
            $translatableListener->setTranslationFallback(false);

            $objectManager->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));

            return;
        }

        $this->setLocale($object, $admin);
    }

    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list')
    {
        $this->getTranslatableListener($admin)->setTranslatableLocale($this->getTranslatableLocale($admin));
        $this->getTranslatableListener($admin)->setTranslationFallback(false);
    }

    /**
     * NEXT_MAJOR: Remove $admin argument.
     *
     * @param AdminInterface $admin Deprecated, set TranslatableListener in the constructor instead
     *
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     *
     * @return TranslatableListener
     */
    protected function getTranslatableListener(AdminInterface $admin)
    {
        // NEXT_MAJOR: Remove this block.
        if (null === $this->translatableListener) {
            $translatableListener = $this->getContainer($admin)->get(
                'stof_doctrine_extensions.listener.translatable'
            );

            \assert($translatableListener instanceof TranslatableListener);

            $this->translatableListener = $translatableListener;
        }

        return $this->translatableListener;
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     */
    private function getManagerRegistry(AdminInterface $admin): ManagerRegistry
    {
        $managerRegistry = $this->managerRegistry ?? $this->getContainer($admin)->get('doctrine');

        \assert($managerRegistry instanceof ManagerRegistry);

        return $managerRegistry;
    }

    /**
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     */
    private function setLocale(object $object, AdminInterface $admin): void
    {
        $translatableLocale = $this->getTranslatableLocale($admin);
        $translatableListener = $this->getTranslatableListener($admin);
        $translatableListener->setTranslatableLocale($translatableLocale);
        $translatableListener->setTranslationFallback(false);

        $objectClassName = ClassUtils::getClass($object);

        // NEXT_MAJOR: Use $this->managerRegistry directly.
        $objectManager = $this->getManagerRegistry($admin)->getManagerForClass($objectClassName);

        \assert($objectManager instanceof ObjectManager);

        $configuration = $translatableListener->getConfiguration($objectManager, $objectClassName);
        if (!isset($configuration['locale'])) {
            throw new \LogicException(sprintf(
                'There is no locale or language property found on class: "%s"',
                \get_class($object)
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
