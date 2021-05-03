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
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Symfony\Component\HttpFoundation\Request;

final class AbstractTranslatableAdminExtensionTest extends TestCase
{
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

        $this->assertSame('es', $this->extension->getTranslatableLocale($admin));
    }

    public function testGetTranslatableLocaleFromDefault(): void
    {
        $admin = $this->createStub(AdminInterface::class);

        $admin->method('hasRequest')->willReturn(false);

        $this->assertSame('es', $this->extension->getTranslatableLocale($admin));
    }
}
