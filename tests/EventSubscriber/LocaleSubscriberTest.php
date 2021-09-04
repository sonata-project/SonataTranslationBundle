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

namespace Sonata\TranslationBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\EventSubscriber\LocaleSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
final class LocaleSubscriberTest extends TestCase
{
    /**
     * Check if LocaleSubscriber set the request locale to the session
     * locale if no locale has been set in the request.
     */
    public function testLocaleSubscriberFromSession(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set('_locale', 'fr');
        $request = new Request();
        $request->setSession($session);
        // Here we simulate that request has previous session
        $request->cookies->add([$session->getName() => null]);
        $event = $this->getEvent($request);
        $localeSubscriber = new LocaleSubscriber();
        $localeSubscriber->onKernelRequest($event);
        static::assertSame('fr', $request->getLocale());
    }

    /**
     * Check if LocaleSubscriber set the session locale to the request
     * _locale routing parameter.
     */
    public function testLocaleSubscriberFromRequest(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set('_locale', 'en');
        $request = new Request();
        $request->setSession($session);
        // Here we simulate that request has previous session
        $request->cookies->add([$session->getName() => null]);
        $request->attributes->set('_locale', 'fr');
        $event = $this->getEvent($request);
        $localeSubscriber = new LocaleSubscriber();
        $localeSubscriber->onKernelRequest($event);
        static::assertSame('fr', $session->get('_locale'));
    }

    /**
     * Check if LocaleSubscriber set the request locale to default
     * value when no locale has been set in request and session.
     */
    public function testLocaleSubscriberDefault(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        // Here we simulate that request has previous session
        $request->cookies->add([$session->getName() => null]);
        $event = $this->getEvent($request);
        $localeSubscriber = new LocaleSubscriber('fr');
        $localeSubscriber->onKernelRequest($event);
        static::assertSame('fr', $request->getLocale());
    }

    private function getEvent(Request $request): RequestEvent
    {
        return new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );
    }
}
