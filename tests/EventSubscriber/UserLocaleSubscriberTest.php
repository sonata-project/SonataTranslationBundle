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
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
class UserLocaleSubscriberTest extends TestCase
{
    /**
     * Check if session locale is set to user locale at login.
     */
    public function testUserLocaleSubscriber(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set('_locale', 'en');
        $request = new Request();
        $request->setSession($session);
        $user = new User('fr');
        $event = $this->getEvent($request, $user);
        $userLocaleSubscriber = new UserLocaleSubscriber($session);
        $userLocaleSubscriber->onInteractiveLogin($event);
        $this->assertSame('fr', $session->get('_locale'));
    }

    private function getEvent(Request $request, User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'dev', []);

        return new InteractiveLoginEvent($request, $token);
    }
}
