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
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelCustomTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelUsingTraitTranslatable;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class TranslatableCheckerTest extends TestCase
{
    use ExpectDeprecationTrait;

    public function testIsTranslatableOnInterface(): void
    {
        $translatableChecker = new TranslatableChecker();

        $object = new ModelTranslatable();

        static::assertFalse($translatableChecker->isTranslatable($object));

        $translatableChecker->setSupportedInterfaces([
            TranslatableInterface::class,
        ]);

        static::assertTrue($translatableChecker->isTranslatable($object));
    }

    public function testIsTranslatableOnModel(): void
    {
        $translatableChecker = new TranslatableChecker();

        $object = new ModelCustomTranslatable();

        static::assertFalse($translatableChecker->isTranslatable($object));

        $translatableChecker->setSupportedModels([
            ModelCustomTranslatable::class,
        ]);

        static::assertTrue($translatableChecker->isTranslatable($object));
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @group legacy
     */
    public function testIsTranslatableOnTrait(): void
    {
        $translatableChecker = new TranslatableChecker();
        $translatableChecker->setSupportedInterfaces([Translatable::class]);

        $object = new ModelUsingTraitTranslatable();

        $this->expectDeprecation('Using traits to specify that a model is translatable is deprecated since sonata-project/translation-bundle 2.x and will be not possible in version 3.0. To mark "Sonata\TranslationBundle\Tests\Fixtures\Model\ModelUsingTraitTranslatable" class as translatable you should implement one of "Gedmo\Translatable\Translatable" interfaces.');

        static::assertTrue($translatableChecker->isTranslatable($object));
    }
}
