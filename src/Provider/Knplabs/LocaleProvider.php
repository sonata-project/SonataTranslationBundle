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

namespace Sonata\TranslationBundle\Provider\Knplabs;

use Knp\DoctrineBehaviors\Contract\Provider\LocaleProviderInterface as KnpLocaleProviderInterface;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface as SonataLocaleProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class LocaleProvider implements KnpLocaleProviderInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private KnpLocaleProviderInterface $localeProvider,
        private SonataLocaleProviderInterface $sonataLocaleProvider
    ) {
    }

    public function provideCurrentLocale(): ?string
    {
        if ($this->isSonataEnabled()) {
            return $this->sonataLocaleProvider->get();
        }

        return $this->localeProvider->provideCurrentLocale();
    }

    public function provideFallbackLocale(): ?string
    {
        if ($this->isSonataEnabled()) {
            return $this->sonataLocaleProvider->get();
        }

        return $this->localeProvider->provideFallbackLocale();
    }

    private function isSonataEnabled(): bool
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (null === $currentRequest) {
            return false;
        }

        return $currentRequest->attributes->has('_sonata_admin');
    }
}
