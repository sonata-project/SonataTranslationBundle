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

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(
        TranslatableChecker $translatableChecker,
        TranslatableListener $translatableListener,
        ManagerRegistry $managerRegistry,
        LocaleProviderInterface $localeProvider
    ) {
        parent::__construct($translatableChecker, $localeProvider);

        $this->translatableListener = $translatableListener;
        $this->managerRegistry = $managerRegistry;
    }

    public function alterObject(AdminInterface $admin, object $object): void
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $this->translatableListener->setTranslatableLocale($this->getTranslatableLocale($admin));
            $this->translatableListener->setTranslationFallback(false);

            $objectManager = $this->managerRegistry->getManagerForClass(\get_class($object));

            \assert($objectManager instanceof ObjectManager);

            $objectManager->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query): void
    {
        $this->translatableListener->setTranslatableLocale($this->getTranslatableLocale($admin));
        $this->translatableListener->setTranslationFallback(false);
    }
}
