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

namespace Sonata\TranslationBundle\Tests\AdminExtension\Knplabs;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\Knplabs\TranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group  translatable-knplabs
 */
class TranslatableAdminExtensionTest extends WebTestCase
{
    /**
     * @var AdminInterface
     */
    protected $admin;

    /**
     * @var TranslatableEntity
     */
    protected $object;

    /**
     * @var TranslatableAdminExtension
     */
    protected $extension;

    protected function setUp()
    {
        $translatableChecker = new TranslatableChecker();
        $translatableChecker->setSupportedInterfaces([
            'Sonata\TranslationBundle\Model\TranslatableInterface',
        ]);
        $this->extension = new TranslatableAdminExtension($translatableChecker);

        $request = $this->prophesize('Symfony\Component\HttpFoundation\Request');
        $request->get('tl')->willReturn('es');

        $this->admin = $this->prophesize('Sonata\AdminBundle\Admin\AdminInterface');
        $this->admin->getRequest()->willReturn($request->reveal());
        $this->admin->hasRequest()->willReturn(true);

        $this->object = new TranslatableEntity();
    }

    public function testSetLocaleForTranslatableObject()
    {
        $this->extension->alterNewInstance($this->admin->reveal(), $this->object);

        $this->assertEquals('es', $this->object->getLocale());
    }

    public function testAlterObjectForTranslatableObject()
    {
        $this->extension->alterObject($this->admin->reveal(), $this->object);

        $this->assertEquals('es', $this->object->getLocale());
    }

    public function testPreUpdate()
    {
        $object = $this->prophesize('Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity');
        $object->mergeNewTranslations()->shouldBeCalled();

        $this->extension->preUpdate($this->admin->reveal(), $object->reveal());
    }

    public function testPrePersist()
    {
        $object = $this->prophesize('Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity');
        $object->mergeNewTranslations()->shouldBeCalled();

        $this->extension->prePersist($this->admin->reveal(), $object->reveal());
    }
}
