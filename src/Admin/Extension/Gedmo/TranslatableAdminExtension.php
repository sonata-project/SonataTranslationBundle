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

/**
 * @final since sonata-project/translation-bundle 2.7
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    /**
     * @var TranslatableListener
     */
    protected $translatableListener;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(
        TranslatableChecker $translatableChecker,
        TranslatableListener $translatableListener,
        ManagerRegistry $managerRegistry,
        string $defaultTranslationLocale
    ) {
        parent::__construct($translatableChecker, $defaultTranslationLocale);

        $this->translatableListener = $translatableListener;
        $this->managerRegistry = $managerRegistry;
    }

    public function alterObject(AdminInterface $admin, object $object): void
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $translatableListener = $this->getTranslatableListener();
            $translatableListener->setTranslatableLocale($this->getTranslatableLocale($admin));
            $translatableListener->setTranslationFallback(false);

            $objectManager = $this->managerRegistry->getManagerForClass(\get_class($object));

            \assert($objectManager instanceof ObjectManager);

            $objectManager->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query): void
    {
        $this->getTranslatableListener()->setTranslatableLocale($this->getTranslatableLocale($admin));
        $this->getTranslatableListener()->setTranslationFallback(false);
    }

    /**
     * @return TranslatableListener
     */
    protected function getTranslatableListener()
    {
        return $this->translatableListener;
    }
}
