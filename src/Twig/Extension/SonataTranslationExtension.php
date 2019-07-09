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
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class SonataTranslationExtension extends AbstractExtension
{
    /**
     * @var TranslatableChecker
     */
    protected $translatableChecker;

    public function __construct(TranslatableChecker $translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    public function getName(): string
    {
        return 'sonata_translation';
    }

    public function setTranslatableChecker(TranslatableChecker $translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    public function getTranslatableChecker(): TranslatableChecker
    {
        return $this->translatableChecker;
    }

    public function getTests()
    {
        return [
            new TwigTest('translatable', [$this, 'isTranslatable']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('localeName', [$this, 'getLocaleName']),
        ];
    }

    /**
     * Check if $object is translatable.
     *
     * @param mixed $object
     */
    public function isTranslatable($object): bool
    {
        return $this->getTranslatableChecker()->isTranslatable($object);
    }

    public function getLocaleName(string $locale, ?string $displayLocale = null): ?string
    {
        return Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale);
    }
}
