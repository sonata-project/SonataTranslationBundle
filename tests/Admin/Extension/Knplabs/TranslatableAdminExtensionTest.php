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

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KnpTranslatableInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Admin\Extension\Knplabs\TranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\DeprecatedTranslatableEntity;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group translatable-knplabs
 */
final class TranslatableAdminExtensionTest extends WebTestCase
{
    use ExpectDeprecationTrait;

    /**
     * @var AdminInterface<TranslatableInterface>
     */
    private $admin;

    /**
     * @var TranslatableEntity
     */
    private $object;

    /**
     * @var TranslatableAdminExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $translatableChecker = new TranslatableChecker();

        $translatableChecker->setSupportedInterfaces([
            KnpTranslatableInterface::class,
            // NEXT_MAJOR: Remove next line.
            TranslatableInterface::class,
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
        // @phpstan-ignore-next-line
        $this->extension->alterNewInstance($this->admin, $this->object);

        static::assertSame('es', $this->getLocale($this->object));
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @group legacy
     */
    public function testDeprecatedSetLocaleForTranslatableObject(): void
    {
        $object = new DeprecatedTranslatableEntity();

        $this->expectDeprecation('Implementing "Sonata\TranslationBundle\Model\TranslatableInterface" for entities using "knplabs/doctrine-behaviors" is deprecated since sonata-project/translation-bundle 2.9 and "Sonata\TranslationBundle\Model\TranslatableInterface::setLocale()" method will not be called anymore in version 3.0.');

        $this->extension->alterNewInstance($this->admin, $object);

        static::assertSame('es', $this->getLocale($object));
    }

    public function testAlterObjectForTranslatableObject(): void
    {
        // @phpstan-ignore-next-line
        $this->extension->alterObject($this->admin, $this->object);

        static::assertSame('es', $this->getLocale($this->object));
    }

    public function testPreUpdate(): void
    {
        $object = $this->createMock(TranslatableEntity::class);
        $object->expects(static::once())->method('mergeNewTranslations');

        // @phpstan-ignore-next-line
        $this->extension->preUpdate($this->admin, $object);
    }

    public function testPrePersist(): void
    {
        $object = $this->createMock(TranslatableEntity::class);
        $object->expects(static::once())->method('mergeNewTranslations');

        // @phpstan-ignore-next-line
        $this->extension->prePersist($this->admin, $object);
    }

    /**
     * NEXT_MAJOR: Replace this call by $object->getCurrentLocale().
     *
     * @param KnpTranslatableInterface|TranslatableInterface $object
     */
    private function getLocale(object $object): ?string
    {
        if ($object instanceof KnpTranslatableInterface
            && !$object instanceof TranslatableInterface
        ) {
            return $object->getCurrentLocale();
        }

        // @phpstan-ignore-next-line
        return $object->getLocale();
    }
}
