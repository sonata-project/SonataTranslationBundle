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
use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;

/**
 * @final since sonata-project/translation-bundle 2.x
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
     * @var ManagerRegistry|null
     */
    private $managerRegistry;

    /**
     * NEXT_MAJOR: Make $translatableListener, $defaultLocale and $managerRegistry mandatory.
     */
    public function __construct(
        TranslatableChecker $translatableChecker,
        ?TranslatableListener $translatableListener = null,
        ?ManagerRegistry $managerRegistry = null,
        ?string $defaultTranslationLocale = null
    ) {
        parent::__construct($translatableChecker, $defaultTranslationLocale);

        if (null === $translatableListener) {
            @trigger_error(sprintf(
                'Not passing an instance of "%s" as argument 2 to "%s()" is deprecated'
                .' since sonata-project/translation-bundle 2.x and will be mandatory in 3.0.',
                TranslatableListener::class,
                __METHOD__
            ), E_USER_DEPRECATED);
        }

        $this->translatableListener = $translatableListener;

        if (null === $managerRegistry) {
            @trigger_error(sprintf(
                'Not passing an instance of "%s" as argument 3 to "%s()" is deprecated'
                .' since sonata-project/translation-bundle 2.x and will be mandatory in 3.0.',
                ManagerRegistry::class,
                __METHOD__
            ), E_USER_DEPRECATED);
        }

        $this->managerRegistry = $managerRegistry;
    }

    public function alterObject(AdminInterface $admin, $object)
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $translatableListener = $this->getTranslatableListener($admin);
            $translatableListener->setTranslatableLocale($this->getTranslatableLocale($admin));
            $translatableListener->setTranslationFallback(false);

            // NEXT_MAJOR: Use $this->managerRegistry directly.
            $this->getManagerRegistry($admin)->getManagerForClass(\get_class($object))->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));
        }
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
     * @return TranslatableListener
     */
    protected function getTranslatableListener(AdminInterface $admin)
    {
        // NEXT_MAJOR: Remove this block.
        if (null === $this->translatableListener) {
            $this->translatableListener = $this->getContainer($admin)->get(
                'stof_doctrine_extensions.listener.translatable'
            );
        }

        return $this->translatableListener;
    }

    /**
     * NEXT_MAJOR: Remove this method.
     */
    private function getManagerRegistry(AdminInterface $admin): ManagerRegistry
    {
        return $this->managerRegistry ?? $this->getContainer($admin)->get('doctrine');
    }
}
