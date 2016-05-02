<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Tests\Model;

use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;
use Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;

class ModelTranslatable extends AbstractTranslatable implements TranslatableInterface
{
}

class ModelPersonalTranslatable extends AbstractPersonalTranslatable implements TranslatableInterface
{
}

class ModelPersonalTranslation extends AbstractPersonalTranslation
{
}

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class GedmoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test AbstractTranslatable
     */
    public function testTranslatableModel()
    {
        $model = new ModelTranslatable();
        $model->setLocale('fr');

        $this->assertSame('fr', $model->getLocale());
        $this->assertTrue($model instanceof \Sonata\TranslationBundle\Model\TranslatableInterface);
    }

    /**
     * @test AbstractPersonalTranslatable and AbstractPersonalTranslation
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

        $this->assertSame(3, count($model->getTranslations()));
    }
}
