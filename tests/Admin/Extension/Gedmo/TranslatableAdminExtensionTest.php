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

namespace Sonata\TranslationBundle\Tests\Admin\Extension\Gedmo;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelTranslatable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class TranslatableAdminExtensionTest extends WebTestCase
{
    /**
     * @var AdminInterface<TranslatableInterface>
     */
    private $admin;

    /**
     * @var ModelTranslatable
     */
    private $object;

    /**
     * @var TranslatableAdminExtension
     */
    private $extension;

    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    protected function setUp(): void
    {
        $translatableChecker = new TranslatableChecker();
        $translatableChecker->setSupportedInterfaces([
            TranslatableInterface::class,
        ]);

        $this->translatableListener = new TranslatableListener();
        $objectManager = $this->createStub(ObjectManager::class);
        $managerRegistry = $this->createStub(ManagerRegistry::class);
        $managerRegistry
            ->method('getManagerForClass')
            ->willReturn($objectManager);

        $this->extension = new TranslatableAdminExtension(
            $translatableChecker,
            $this->translatableListener,
            $managerRegistry,
            'es'
        );

        $request = new Request();
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'es');

        $this->admin = $this->createMock(AdminInterface::class);
        $this->admin->method('getRequest')->willReturn($request);
        $this->admin->method('hasRequest')->willReturn(true);

        $this->object = new ModelTranslatable();
    }

    public function testSetLocaleForTranslatableObject(): void
    {
        $this->extension->alterNewInstance($this->admin, $this->object);

        static::assertSame('es', $this->object->getLocale());
    }

    public function testAlterObjectForTranslatableObject(): void
    {
        $this->extension->alterObject($this->admin, $this->object);

        static::assertSame('es', $this->object->getLocale());
    }

    public function testConfigureQuery(): void
    {
        $query = $this->createStub(ProxyQueryInterface::class);

        $this->extension->configureQuery($this->admin, $query);

        static::assertSame('es', $this->translatableListener->getListenerLocale());
        static::assertFalse($this->translatableListener->getTranslationFallback());
    }
}
