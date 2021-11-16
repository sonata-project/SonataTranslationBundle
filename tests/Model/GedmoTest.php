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
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\DeprecatedModelTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelPersonalTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Model\ModelPersonalTranslation;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class GedmoTest extends TestCase
{
    public function testTranslatableModel(): void
    {
        $model = new DeprecatedModelTranslatable();
        $model->setLocale('fr');

        static::assertSame('fr', $model->getLocale());
        static::assertInstanceOf(TranslatableInterface::class, $model);
    }

    public function testPersonalTranslatableModel(): void
    {
        $model = new ModelPersonalTranslatable();
        $model->setLocale('fr');

        static::assertSame('fr', $model->getLocale());
        static::assertInstanceOf(TranslatableInterface::class, $model);

        $model->addTranslation(new ModelPersonalTranslation('en', 'title', 'Title en'));
        $model->addTranslation(new ModelPersonalTranslation('it', 'title', 'Title it'));
        $model->addTranslation(new ModelPersonalTranslation('es', 'title', 'Title es'));

        static::assertSame('Title en', $model->getTranslation('title', 'en'));
        static::assertSame('Title it', $model->getTranslation('title', 'it'));
        static::assertSame('Title es', $model->getTranslation('title', 'es'));

        static::assertCount(3, $model->getTranslations());
    }
}
