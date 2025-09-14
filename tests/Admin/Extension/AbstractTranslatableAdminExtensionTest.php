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
use Sonata\TranslationBundle\Provider\RequestLocaleProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class AbstractTranslatableAdminExtensionTest extends TestCase
{
    /**
     * @var AbstractTranslatableAdminExtension<object>
     */
    private AbstractTranslatableAdminExtension $extension;

    protected function setUp(): void
    {
        $translatableChecker = new TranslatableChecker();

        $localeProvider = new class implements LocaleProviderInterface {
            public function get(): string
            {
                return 'es';
            }
        };

        $this->extension = new /**
             * @template-extends AbstractTranslatableAdminExtension<object>
             */ class($translatableChecker, $localeProvider) extends AbstractTranslatableAdminExtension {};
    }

    public function testSetsPersistentParameters(): void
    {
        $parameters = $this->extension->configurePersistentParameters(static::createStub(AdminInterface::class), []);

        static::assertSame(['tl' => 'es'], $parameters);
    }

    public function testReset(): void
    {
        $request = new Request();
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'cs');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $localeProvider = new RequestLocaleProvider($requestStack, 'en');

        $translatableChecker = new TranslatableChecker();

        $extension = new
            /**
             * @template-extends AbstractTranslatableAdminExtension<object>
             */
            class($translatableChecker, $localeProvider) extends AbstractTranslatableAdminExtension {
                public function getLocalePublicly(): string
                {
                    return $this->getTranslatableLocale();
                }
            };

        static::assertSame('cs', $extension->getLocalePublicly());
        $request->query->set(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER, 'es');
        $localeProvider->reset();
        static::assertSame('cs', $extension->getLocalePublicly());
        $extension->reset();
        static::assertSame('es', $extension->getLocalePublicly());
    }
}
