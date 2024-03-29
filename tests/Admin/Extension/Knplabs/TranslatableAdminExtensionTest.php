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

namespace Sonata\TranslationBundle\Tests\Admin\Extension\Knplabs;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KnpTranslatableInterface;
use PHPUnit\Framework\MockObject\Stub;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Admin\Extension\Knplabs\TranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group translatable-knplabs
 */
final class TranslatableAdminExtensionTest extends WebTestCase
{
    /**
     * @var AdminInterface<TranslatableInterface>&Stub
     */
    private Stub $admin;

    private TranslatableEntity $object;

    private TranslatableAdminExtension $extension;

    protected function setUp(): void
    {
        $translatableChecker = new TranslatableChecker();

        $translatableChecker->setSupportedInterfaces([
            KnpTranslatableInterface::class,
        ]);

        $localeProvider = new class() implements LocaleProviderInterface {
            public function get(): string
            {
                return 'es';
            }
        };

        $this->extension = new TranslatableAdminExtension($translatableChecker, $localeProvider);

        $request = new Request();
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'es');

        $this->admin = $this->createStub(AdminInterface::class);
        $this->admin->method('getRequest')->willReturn($request);
        $this->admin->method('hasRequest')->willReturn(true);

        $this->object = new TranslatableEntity();
    }

    public function testSetLocaleForTranslatableObject(): void
    {
        $this->extension->alterNewInstance($this->admin, $this->object);

        static::assertSame('es', $this->object->getCurrentLocale());
    }

    public function testAlterObjectForTranslatableObject(): void
    {
        $this->extension->alterObject($this->admin, $this->object);

        static::assertSame('es', $this->object->getCurrentLocale());
    }

    public function testPreUpdate(): void
    {
        $object = $this->createMock(TranslatableEntity::class);
        $object->expects(static::once())->method('mergeNewTranslations');

        $this->extension->preUpdate($this->admin, $object);
    }

    public function testPrePersist(): void
    {
        $object = $this->createMock(TranslatableEntity::class);
        $object->expects(static::once())->method('mergeNewTranslations');

        $this->extension->prePersist($this->admin, $object);
    }
}
