<?php

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
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;
use Sonata\TranslationBundle\Traits\TranslatableTrait;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class GedmoTest extends TestCase
{
    /**
     * @test TranslatableTrait
     */
    public function testTranslatableModel()
    {
        $model = new ModelTranslatable();
        $model->setLocale('fr');

        $this->assertSame('fr', $model->getLocale());
        $this->assertTrue($model instanceof \Sonata\TranslationBundle\Model\TranslatableInterface);
    }

    /**
     * @test PersonalTranslatableTrait
     */
    public function testPersonalTranslatableModel()
    {
        $model = new ModelPersonalTranslatable();
        $model->setLocale('fr');

        $this->assertSame('fr', $model->getLocale());
        $this->assertTrue($model instanceof \Sonata\TranslationBundle\Model\TranslatableInterface);

        $model->addTranslation(new ModelPersonalTranslation('en', 'title', 'Title en'));
        $model->addTranslation(new ModelPersonalTranslation('it', 'title', 'Title it'));
        $model->addTranslation(new ModelPersonalTranslation('es', 'title', 'Title es'));

        $this->assertSame('Title en', $model->getTranslation('title', 'en'));
        $this->assertSame('Title it', $model->getTranslation('title', 'it'));
        $this->assertSame('Title es', $model->getTranslation('title', 'es'));

        $this->assertCount(3, $model->getTranslations());
    }
}
