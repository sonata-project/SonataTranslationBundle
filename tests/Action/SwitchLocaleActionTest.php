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

namespace Sonata\TranslationBundle\Tests\Action;

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Action\SwitchLocaleAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
final class SwitchLocaleActionTest extends TestCase
{
    public function testSwitchLocaleAction(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set('_locale', 'en');
        $request = new Request();
        $request->setSession($session);
        $action = new SwitchLocaleAction();
        $action($request, 'fr');
        static::assertSame('fr', $session->get('_locale'));
    }
}
