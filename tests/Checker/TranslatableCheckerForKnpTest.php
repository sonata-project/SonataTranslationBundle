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

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KnpTranslatableInterface;
use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;

/**
 * @author Alfonso Machado <email@alfonsomachado.com>
 */
final class TranslatableCheckerForKnpTest extends TestCase
{
    public function testIsTranslatableOnInterface(): void
    {
        $translatableChecker = new TranslatableChecker();

        $object = new TranslatableEntity();

        static::assertFalse($translatableChecker->isTranslatable($object));

        // NEXT_MAJOR: Only leave KnpTranslatableInterface.
        $knpInterfaces = [TranslatableInterface::class];

        if (interface_exists(KnpTranslatableInterface::class)) {
            $knpInterfaces[] = KnpTranslatableInterface::class;
        }

        $translatableChecker->setSupportedInterfaces($knpInterfaces);

        static::assertTrue($translatableChecker->isTranslatable($object));
    }
}
