<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Admin\Extension\Gedmo;

use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AdminInterface;
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

    public function __construct(TranslatableChecker $translatableChecker, TranslatableListener $translatableListener)
    {
        parent::__construct($translatableChecker);
        $this->translatableListener = $translatableListener;
    }

    /**
     * @param AdminInterface $admin
     *
     * @return TranslatableListener
     */
    protected function getTranslatableListener()
    {
        return $this->translatableListener;
    }
    
    /**
     * @param AdminInterface $admin
     * 
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getManagerFromAdmin(AdminInterface $admin)
    {
        $service = $this->getContainer($admin)->get($admin->getManagerType());
        
        if (!$service) {
            $service = $this->getContainer($admin)->get('doctrine');
        }
        
        return $service->getManager();
    }

    /**
     * {@inheritdoc}
     */
    public function alterObject(AdminInterface $admin, $object)
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $this->getTranslatableListener()->setTranslatableLocale($this->getTranslatableLocale($admin));
            $this->getTranslatableListener()->setTranslationFallback('');

            $this->getManagerFromAdmin($admin)->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }
}
