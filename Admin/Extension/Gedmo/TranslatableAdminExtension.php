<?php

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
            $translatableListener->setTranslationFallback('');

            $this->getContainer($admin)->get('doctrine')->getManager()->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    /**
     * @param AdminInterface $admin Deprecated, set TranslatableListener in the constructor instead.
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
