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

use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\Intl\Languages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class IntlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('sonata_language_name', [$this, 'getLanguageName']),
        ];
    }

    /**
     * @see https://github.com/twigphp/intl-extra/blob/v3.0.3/src/IntlExtension.php#L185
     */
    public function getLanguageName(string $language, string $locale): string
    {
        try {
            return Languages::getName($language, $locale);
        } catch (MissingResourceException) {
            return $language;
        }
    }
}
