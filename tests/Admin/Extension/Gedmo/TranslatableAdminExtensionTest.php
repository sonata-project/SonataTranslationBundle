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

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Translatable\Translatable;
use Gedmo\Translatable\TranslatableListener;
use PHPUnit\Framework\MockObject\Stub;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelTranslatable;
use Sonata\TranslationBundle\Tests\Traits\DoctrineOrmTestCase;
use Symfony\Component\HttpFoundation\Request;

final class TranslatableAdminExtensionTest extends DoctrineOrmTestCase
{
    /**
     * @var AdminInterface<Translatable>&Stub
     */
    private Stub $admin;

    private TranslatableAdminExtension $extension;

    private TranslatableListener $translatableListener;

    private EntityManager $em;

    protected function setUp(): void
    {
        $translatableChecker = new TranslatableChecker();
        $translatableChecker->setSupportedInterfaces([
            Translatable::class,
        ]);

        $evm = new EventManager();
        $this->translatableListener = new TranslatableListener();
        $this->translatableListener->setTranslatableLocale('en');
        $this->translatableListener->setDefaultLocale('en');
        $evm->addEventSubscriber($this->translatableListener);
        $this->em = $this->getMockSqliteEntityManager($evm);
        $managerRegistry = $this->createStub(ManagerRegistry::class);
        $managerRegistry
            ->method('getManagerForClass')
            ->willReturn($this->em);

        $localeProvider = new class() implements LocaleProviderInterface {
            public function get(): string
            {
                return 'es';
            }
        };

        $this->extension = new TranslatableAdminExtension(
            $translatableChecker,
            $this->translatableListener,
            $managerRegistry,
            $localeProvider
        );

        $request = new Request();
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'es');

        $this->admin = $this->createStub(AdminInterface::class);
        $this->admin->method('getRequest')->willReturn($request);
        $this->admin->method('hasRequest')->willReturn(true);
    }

    public function testSetLocaleForTranslatableObject(): void
    {
        $object = new ModelTranslatable();

        $this->extension->alterNewInstance($this->admin, $object);

        static::assertSame('es', $object->locale);
    }

    public function testAlterObjectForTranslatableObject(): void
    {
        $object = new ModelTranslatable();
        $this->em->persist($object);
        $this->em->flush();

        $this->extension->alterObject($this->admin, $object);

        static::assertSame('es', $object->locale);
    }

    public function testConfigureQuery(): void
    {
        $query = $this->createStub(ProxyQueryInterface::class);

        $this->extension->configureQuery($this->admin, $query);

        static::assertSame('es', $this->translatableListener->getListenerLocale());
        static::assertFalse($this->translatableListener->getTranslationFallback());
    }

    public function testObjectIsRefreshedWithDifferentLocale(): void
    {
        $object = new ModelTranslatable();
        $object->locale = 'en';
        $this->em->persist($object);
        $this->em->flush();

        $object->refreshableField = 'new value';

        $this->extension->alterObject($this->admin, $object);

        /** @psalm-suppress TypeDoesNotContainType */
        static::assertSame('', $object->refreshableField);
    }

    public function testObjectIsNotRefreshedWithTheSameLocale(): void
    {
        $object = new ModelTranslatable();
        $object->locale = 'es';
        $this->em->persist($object);
        $this->em->flush();

        $object->refreshableField = 'new value';

        $this->extension->alterObject($this->admin, $object);

        /** @psalm-suppress RedundantCondition */
        static::assertSame('new value', $object->refreshableField);
    }

    protected function getUsedEntityFixtures(): array
    {
        return [ModelTranslatable::class];
    }
}
