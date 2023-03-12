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

namespace Sonata\TranslationBundle\Tests\Provider\Knplabs;

use Knp\DoctrineBehaviors\Contract\Provider\LocaleProviderInterface;
use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Provider\Knplabs\LocaleProvider;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface as SonataLocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class LocaleProviderTest extends TestCase
{
    /**
     * @dataProvider provideValues
     */
    public function testUsesTheProperProviderBasedOnRequest(
        string $expectedLocale,
        string $knpLocale,
        string $sonataLocale,
        bool $isAdminEnabled
    ): void {
        $request = new Request();

        if ($isAdminEnabled) {
            $request->attributes->set('_sonata_admin', 'code');
        }

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $knpProvider = $this->createKnpProvider($knpLocale);

        $sonataProvider = $this->createSonataProvider($sonataLocale);

        $localeProvider = new LocaleProvider($requestStack, $knpProvider, $sonataProvider);

        static::assertSame($expectedLocale, $localeProvider->provideCurrentLocale());
        static::assertSame($expectedLocale, $localeProvider->provideFallbackLocale());
    }

    /**
     * @return iterable<array{string, string, string, bool}>
     */
    public function provideValues(): iterable
    {
        yield 'with sonata enabled it uses sonata provider' => ['en', 'es', 'en', true];
        yield 'with sonata disabled it uses knp provider' => ['es', 'es', 'en', false];
    }

    private function createKnpProvider(string $locale): LocaleProviderInterface
    {
        return new class($locale) implements LocaleProviderInterface {
            public function __construct(private string $locale)
            {
            }

            public function provideCurrentLocale(): ?string
            {
                return $this->locale;
            }

            public function provideFallbackLocale(): ?string
            {
                return $this->locale;
            }
        };
    }

    private function createSonataProvider(string $locale): SonataLocaleProviderInterface
    {
        return new class($locale) implements SonataLocaleProviderInterface {
            public function __construct(private string $locale)
            {
            }

            public function get(): string
            {
                return $this->locale;
            }
        };
    }
}
