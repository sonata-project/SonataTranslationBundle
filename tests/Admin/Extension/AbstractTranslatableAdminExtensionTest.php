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

namespace Sonata\TranslationBundle\Tests\Admin\Extension;

use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

final class AbstractTranslatableAdminExtensionTest extends TestCase
{
    use ExpectDeprecationTrait;

    /**
     * @var AbstractTranslatableAdminExtension
     */
    private $extension;

    /**
     * @var TranslatableChecker
     */
    private $translatableChecker;

    protected function setUp(): void
    {
        $this->translatableChecker = new TranslatableChecker();
        $this->translatableChecker->setSupportedInterfaces([
            TranslatableInterface::class,
        ]);

        $this->extension = new class($this->translatableChecker, 'es') extends AbstractTranslatableAdminExtension {
        };
    }

    public function testGetTranslatableLocaleFromRequest(): void
    {
        $request = new Request();
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'es');

        $admin = $this->createStub(AdminInterface::class);

        $admin->method('getRequest')->willReturn($request);
        $admin->method('hasRequest')->willReturn(true);

        static::assertSame('es', $this->extension->getTranslatableLocale($admin));
    }

    public function testGetTranslatableLocaleFromDefault(): void
    {
        $admin = $this->createStub(AdminInterface::class);

        $admin->method('hasRequest')->willReturn(false);

        static::assertSame('es', $this->extension->getTranslatableLocale($admin));
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @group legacy
     */
    public function testGetTranslatableLocaleFromContainer(): void
    {
        $admin = $this->createStub(AbstractAdmin::class);
        $admin->method('hasRequest')->willReturn(false);

        $container = new Container();
        $container->setParameter('sonata_translation.default_locale', 'es');

        $pool = $this->createStub(Pool::class);
        $pool->method('getContainer')->willReturn($container);

        $admin
            ->method('getConfigurationPool')
            ->willReturn($pool);

        $this->expectDeprecation('Omitting the argument 2 or passing other type than "string" to "Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension::__construct()" is deprecated since sonata-project/translation-bundle 2.7 and will be not possible in version 3.0.');

        $extension = new class($this->translatableChecker) extends AbstractTranslatableAdminExtension {
        };

        /* @phpstan-ignore-next-line */
        static::assertSame('es', $extension->getTranslatableLocale($admin));
    }
}
