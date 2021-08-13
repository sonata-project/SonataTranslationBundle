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

namespace Sonata\TranslationBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ModelPersonalTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ModelPersonalTranslation;
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ModelTranslatable;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class GedmoTest extends TestCase
{
    public function testTranslatableModel(): void
    {
        $model = new ModelTranslatable();
        $model->setLocale('fr');

        self::assertSame('fr', $model->getLocale());
    }

    public function testPersonalTranslatableModel(): void
    {
        $model = new ModelPersonalTranslatable();
        $model->setLocale('fr');

        self::assertSame('fr', $model->getLocale());

        $model->addTranslation(new ModelPersonalTranslation('en', 'title', 'Title en'));
        $model->addTranslation(new ModelPersonalTranslation('it', 'title', 'Title it'));
        $model->addTranslation(new ModelPersonalTranslation('es', 'title', 'Title es'));

        self::assertSame('Title en', $model->getTranslation('title', 'en'));
        self::assertSame('Title it', $model->getTranslation('title', 'it'));
        self::assertSame('Title es', $model->getTranslation('title', 'es'));

        self::assertCount(3, $model->getTranslations());
    }
}
