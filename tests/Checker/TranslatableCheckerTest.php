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

namespace Sonata\TranslationBundle\Tests\Checker;

use Gedmo\Translatable\Translatable;
use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelCustomTranslatable;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class TranslatableCheckerTest extends TestCase
{
    private TranslatableChecker $translatableChecker;

    protected function setUp(): void
    {
        $this->translatableChecker = new TranslatableChecker();
    }

    /**
     * @return iterable<array{object|class-string, array<class-string>, array<class-string>}>
     */
    public function provideIsTranslatableCases(): iterable
    {
        yield 'object-by-model' => [
            new ModelCustomTranslatable(),
            [ModelCustomTranslatable::class],
            [],
        ];

        yield 'class-by-model' => [
            ModelCustomTranslatable::class,
            [ModelCustomTranslatable::class],
            [],
        ];

        yield 'object-by-interfaces' => [
            $this->createMock(Translatable::class),
            [],
            [Translatable::class],
        ];

        yield 'class-by-interfaces' => [
            Translatable::class,
            [],
            [Translatable::class],
        ];
    }

    /**
     * @dataProvider provideIsTranslatableCases
     *
     * @phpstan-param object|class-string $classOrObject
     * @phpstan-param class-string[] $supportedModels
     * @phpstan-param class-string[] $supportedInterfaces
     */
    public function testIsTranslatable($classOrObject, array $supportedModels, array $supportedInterfaces): void
    {
        static::assertFalse($this->translatableChecker->isTranslatable($classOrObject));

        $this->translatableChecker->setSupportedModels($supportedModels);
        $this->translatableChecker->setSupportedInterfaces($supportedInterfaces);

        static::assertTrue($this->translatableChecker->isTranslatable($classOrObject));
    }

    public function testIsTranslatableNull(): void
    {
        static::assertFalse($this->translatableChecker->isTranslatable(null));
    }

    public function testIsTranslatableNoMatch(): void
    {
        $this->translatableChecker->setSupportedModels([ModelCustomTranslatable::class]);
        $this->translatableChecker->setSupportedInterfaces([Translatable::class]);

        static::assertFalse($this->translatableChecker->isTranslatable(\stdClass::class));
    }
}
