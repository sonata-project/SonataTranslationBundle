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

namespace Sonata\TranslationBundle\Twig\Extension;

use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\ResourceBundle\LocaleBundleInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class SonataTranslationExtension extends \Twig_Extension
{
    /**
     * @var TranslatableChecker
     */
    protected $translatableChecker;

    /**
     * @var LocaleBundleInterface
     */
    private $localeBundle;

    /**
     * @param TranslatableChecker $translatableChecker
     */
    public function __construct(TranslatableChecker $translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_translation';
    }

    /**
     * @param TranslatableChecker $translatableChecker
     */
    public function setTranslatableChecker($translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    /**
     * @return TranslatableChecker
     */
    public function getTranslatableChecker()
    {
        return $this->translatableChecker;
    }

    public function getLocaleBundle(): LocaleBundleInterface
    {
        return $this->localeBundle instanceof LocaleBundleInterface ? $this->localeBundle : ($this->localeBundle = Intl::getLocaleBundle());
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('translatable', [$this, 'isTranslatable']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('localeName', [$this, 'getLocaleName']),
        ];
    }

    /**
     * Check if $object is translatable.
     *
     * @param mixed $object
     *
     * @return bool
     */
    public function isTranslatable($object)
    {
        return $this->getTranslatableChecker()->isTranslatable($object);
    }

    public function getLocaleName(string $locale, ?string $displayLocale = null): ?string
    {
        return $this->getLocaleBundle()->getLocaleName($locale, $displayLocale);
    }
}
