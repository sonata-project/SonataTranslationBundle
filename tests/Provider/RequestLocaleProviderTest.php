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

namespace Sonata\TranslationBundle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Provider\RequestLocaleProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestLocaleProviderTest extends TestCase
{
    public function testGet(): void
    {
        $request = new Request();
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'en');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $requestLocaleProvider = new RequestLocaleProvider($requestStack, 'es');

        static::assertSame('en', $requestLocaleProvider->get());
    }

    public function testWithoutRequestGetsDefaultLocale(): void
    {
        $requestLocaleProvider = new RequestLocaleProvider(new RequestStack(), 'en');

        static::assertSame('en', $requestLocaleProvider->get());
    }

    public function testWithoutRequestParameterGetsDefaultLocale(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        $requestLocaleProvider = new RequestLocaleProvider($requestStack, 'en');

        static::assertSame('en', $requestLocaleProvider->get());
    }
}
