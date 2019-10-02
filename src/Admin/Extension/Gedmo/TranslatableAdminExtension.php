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

use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    /**
     * @var TranslatableListener
     */
    protected $translatableListener;

    public function __construct(TranslatableChecker $translatableChecker, TranslatableListener $translatableListener = null)
    {
        parent::__construct($translatableChecker);
        $this->translatableListener = $translatableListener;
    }

    /**
     * {@inheritdoc}
     */
    public function alterObject(AdminInterface $admin, $object)
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $translatableListener = $this->getTranslatableListener($admin);
            $translatableListener->setTranslatableLocale($this->getTranslatableLocale($admin));
            $translatableListener->setTranslationFallback(false);

            $this->getContainer($admin)->get('doctrine')->getManagerForClass(\get_class($object))->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list')
    {
        $this->getTranslatableListener($admin)->setTranslatableLocale($this->getTranslatableLocale($admin));
        $this->getTranslatableListener($admin)->setTranslationFallback(false);
    }

    /**
     * @param AdminInterface $admin Deprecated, set TranslatableListener in the constructor instead
     *
     * @return TranslatableListener
     */
    protected function getTranslatableListener(AdminInterface $admin)
    {
        if (null === $this->translatableListener) {
            $this->translatableListener = $this->getContainer($admin)->get(
                'stof_doctrine_extensions.listener.translatable'
            );
        }

        return $this->translatableListener;
    }
}
