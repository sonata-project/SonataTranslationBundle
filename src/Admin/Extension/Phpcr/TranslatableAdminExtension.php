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

namespace Sonata\TranslationBundle\Admin\Extension\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Translation\LocaleChooser\LocaleChooser;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    /**
     * @var LocaleChooser
     */
    private $localeChooser;

    public function __construct(
        TranslatableChecker $translatableChecker,
        LocaleChooser $localeChooser,
        string $defaultTranslationLocale
    ) {
        parent::__construct($translatableChecker, $defaultTranslationLocale);
        $this->localeChooser = $localeChooser;
    }

    public function alterObject(AdminInterface $admin, object $object): void
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

    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query): void
    {
        $this->localeChooser->setLocale($this->getTranslatableLocale($admin));
    }

    /**
     * @template T of object
     * @phpstan-param AdminInterface<T> $admin
     */
    private function getDocumentManager(AdminInterface $admin): DocumentManager
    {
        return $admin->getModelManager()->getDocumentManager();
    }
}
