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

namespace Sonata\AdminBundle\Tests\Checker;

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;

/**
 * @author Alfonso Machado <email@alfonsomachado.com>
 */
class TranslatableCheckerForKnpTest extends TestCase
{
    /**
     * @test TranslatableChecker::isTranslatable
     */
    public function testIsTranslatableOnInterface()
    {
        $translatableChecker = new TranslatableChecker();

        $object = new TranslatableEntity();

        $this->assertFalse($translatableChecker->isTranslatable($object));

        $translatableChecker->setSupportedInterfaces([
            'Sonata\TranslationBundle\Model\TranslatableInterface',
        ]);

        $this->assertTrue($translatableChecker->isTranslatable($object));
    }
}
