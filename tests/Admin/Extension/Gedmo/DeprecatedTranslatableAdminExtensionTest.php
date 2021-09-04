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
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelTranslatable;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * NEXT_MAJOR: Remove this class.
 *
 * @group legacy
 */
final class DeprecatedTranslatableAdminExtensionTest extends WebTestCase
{
    use ExpectDeprecationTrait;

    /**
     * @var AbstractAdmin<TranslatableInterface>
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

        $this->expectDeprecation('Omitting the argument 2 or passing other type than "string" to "Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension::__construct()" is deprecated since sonata-project/translation-bundle 2.7 and will be not possible in version 3.0.');
        $this->expectDeprecation('Not passing an instance of "Gedmo\Translatable\TranslatableListener" as argument 2 to "Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension::__construct()" is deprecated since sonata-project/translation-bundle 2.7 and will be mandatory in 3.0.');
        $this->expectDeprecation('Not passing an instance of "Doctrine\Persistence\ManagerRegistry" as argument 3 to "Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension::__construct()" is deprecated since sonata-project/translation-bundle 2.7 and will be mandatory in 3.0.');

        $this->extension = new TranslatableAdminExtension(
            $translatableChecker
        );

        $request = new Request();
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'es');

        $this->admin = $this->createStub(AbstractAdmin::class);
        $this->admin->method('getRequest')->willReturn($request);
        $this->admin->method('hasRequest')->willReturn(true);

        $this->translatableListener = new TranslatableListener();

        $container = new Container();
        $container->set('stof_doctrine_extensions.listener.translatable', $this->translatableListener);

        $objectManager = $this->createStub(ObjectManager::class);
        $managerRegistry = $this->createStub(ManagerRegistry::class);
        $managerRegistry
            ->method('getManagerForClass')
            ->willReturn($objectManager);

        $container->set('doctrine', $managerRegistry);

        $pool = $this->createStub(Pool::class);
        $pool->method('getContainer')->willReturn($container);

        $this->admin
            ->method('getConfigurationPool')
            ->willReturn($pool);

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
