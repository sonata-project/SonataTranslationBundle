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
use Sonata\TranslationBundle\EventSubscriber\UserLocaleSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
final class UserLocaleSubscriberTest extends TestCase
{
    /**
     * Check if session locale is set to user locale at login.
     *
     * @dataProvider userLocaleSubscriberDataProvider
     */
    public function testUserLocaleSubscriber(UserInterface $user, string $expectedLocale): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set('_locale', 'en');
        $request = new Request([], [], [], [$session->getName() => null]);
        $request->setSession($session);
        $event = $this->getEvent($request, $user);
        $userLocaleSubscriber = new UserLocaleSubscriber();
        static::assertTrue($session->isStarted());
        $userLocaleSubscriber->onInteractiveLogin($event);
        static::assertSame($expectedLocale, $session->get('_locale'));
    }

    /**
     * @return array{array{0: UserInterface, 1: string}}
     */
    public function userLocaleSubscriberDataProvider(): array
    {
        return [
            [new LocalizedUser('fr'), 'fr'],
            [new User(), 'en'],
        ];
    }

    /**
     * Ensure session is not started if there is no previous session.
     */
    public function testUserLocaleSubscriberWithoutPreviousSession(): void
    {
        $user = new LocalizedUser('es_AR');
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        $event = $this->getEvent($request, $user);
        $userLocaleSubscriber = new UserLocaleSubscriber();
        $userLocaleSubscriber->onInteractiveLogin($event);
        static::assertFalse($session->isStarted());
        static::assertNull($session->get('_locale'));
        $session->set('_locale', 'en');
        static::assertTrue($session->isStarted());
        $userLocaleSubscriber->onInteractiveLogin($event);
        static::assertSame('en', $session->get('_locale'));
    }

    private function getEvent(Request $request, UserInterface $user): InteractiveLoginEvent
    {
        $token = new UsernamePasswordToken($user, null, 'dev', []);

        return new InteractiveLoginEvent($request, $token);
    }
}
