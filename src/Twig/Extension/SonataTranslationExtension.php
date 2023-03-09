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
use Symfony\Component\Intl\Locales;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class SonataTranslationExtension extends AbstractExtension
{
    public function __construct(protected TranslatableChecker $translatableChecker)
    {
    }

    public function setTranslatableChecker(TranslatableChecker $translatableChecker): void
    {
        $this->translatableChecker = $translatableChecker;
    }

    public function getTranslatableChecker(): TranslatableChecker
    {
        return $this->translatableChecker;
    }

    public function getTests(): array
    {
        return [
            new TwigTest('translatable', [$this, 'isTranslatable']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('localeName', [$this, 'getLocaleName']),
        ];
    }

    /**
     * Check if $object is translatable.
     *
     * @param object|string|null $object
     *
     * @phpstan-param object|class-string|null $object
     */
    public function isTranslatable($object): bool
    {
        return $this->getTranslatableChecker()->isTranslatable($object);
    }

    public function getLocaleName(string $locale, ?string $displayLocale = null): ?string
    {
        return Locales::getName($locale, $displayLocale);
    }
}
