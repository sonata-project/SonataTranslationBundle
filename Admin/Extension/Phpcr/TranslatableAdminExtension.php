<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Admin\Extension\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    /**
     * {@inheritdoc}
     */
    public function alterObject(AdminInterface $admin, $object)
    {
        $locale = $this->getTranslatableLocale($admin);
        $documentManager = $this->getDocumentManager($admin);
        $unitOfWork = $documentManager->getUnitOfWork();

        if (
            $this->getTranslatableChecker()->isTranslatable($object)
            && ($unitOfWork->getCurrentLocale($object) !== $locale)
        ) {
            $object = $this->getDocumentManager($admin)->findTranslation($admin->getClass(), $object->getId(), $locale);

            // if the translation did not yet exists, the locale will be
            // the fallback locale. This makes sure the new locale is set.
            if ($unitOfWork->getCurrentLocale($object) !== $locale) {
                $documentManager->bindTranslation($object, $locale);
            }
        }
    }

    /**
     * @param AdminInterface $admin
     *
     * @return DocumentManager
     */
    protected function getDocumentManager(AdminInterface $admin)
    {
        return $admin->getModelManager()->getDocumentManager();
    }
}
