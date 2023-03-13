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
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;

final class AbstractTranslatableAdminExtensionTest extends TestCase
{
    /**
     * @var AbstractTranslatableAdminExtension<object>
     */
    private AbstractTranslatableAdminExtension $extension;

    private TranslatableChecker $translatableChecker;

    /**
     * @psalm-suppress InternalClass https://github.com/vimeo/psalm/issues/6315
     */
    protected function setUp(): void
    {
        $this->translatableChecker = new TranslatableChecker();

        $localeProvider = new class() implements LocaleProviderInterface {
            public function get(): string
            {
                return 'es';
            }
        };

        $this->extension = new /**
             * @template-extends AbstractTranslatableAdminExtension<object>
             */ class($this->translatableChecker, $localeProvider) extends AbstractTranslatableAdminExtension {};
    }

    public function testSetsPersistentParameters(): void
    {
        $parameters = $this->extension->configurePersistentParameters($this->createStub(AdminInterface::class), []);

        static::assertSame(['tl' => 'es'], $parameters);
    }
}
