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

namespace Sonata\TranslationBundle\Provider;

use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestLocaleProvider implements LocaleProviderInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $defaultTranslationLocale;

    /**
     * @var string|null
     */
    private $translatableLocale;

    public function __construct(RequestStack $requestStack, string $defaultTranslationLocale)
    {
        $this->requestStack = $requestStack;
        $this->defaultTranslationLocale = $defaultTranslationLocale;
    }

    public function get(): string
    {
        if (null === $this->translatableLocale) {
            $this->translatableLocale = $this->getFromRequestOrDefault();
        }

        return $this->translatableLocale;
    }

    private function getFromRequestOrDefault(): string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (null === $currentRequest) {
            return $this->defaultTranslationLocale;
        }

        $locale = $currentRequest->query->get(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, $this->defaultTranslationLocale);

        \assert(\is_string($locale));

        return $locale;
    }
}
