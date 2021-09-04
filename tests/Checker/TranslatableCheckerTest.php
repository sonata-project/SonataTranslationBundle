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

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelCustomTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelUsingTraitTranslatable;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class TranslatableCheckerTest extends TestCase
{
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

    public function testIsTranslatableOnTrait(): void
    {
        $translatableChecker = new TranslatableChecker();

        $object = new ModelUsingTraitTranslatable();

        static::assertTrue($translatableChecker->isTranslatable($object));
    }
}
