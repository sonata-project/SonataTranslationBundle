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

namespace Sonata\TranslationBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;

/**
 * @author Omar Jbara <omar.jbara2@gmail.com>
 */
final class KnplabsTest extends TestCase
{
    public function testTranslatableModel(): void
    {
        $model = new TranslatableEntity();
        $model->setLocale('fr');

        static::assertSame('fr', $model->getLocale());
        static::assertInstanceOf(TranslatableInterface::class, $model);
    }
}
