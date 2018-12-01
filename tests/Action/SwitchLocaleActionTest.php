<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Action\SwitchLocaleAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
class SwitchLocaleActionTest extends TestCase
{
    public function testSwitchLocaleAction()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set('_locale', 'en');
        $request = new Request();
        $request->setSession($session);
        $action = new SwitchLocaleAction();
        $action($request, 'fr');
        $this->assertSame('fr', $session->get('_locale'));
    }
}
