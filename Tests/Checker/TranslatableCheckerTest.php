<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Tests\Checker;

use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\AbstractTranslatable;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Translatable;

class ModelTranslatable extends AbstractTranslatable implements TranslatableInterface
{
}

class ModelCustomTranslatable
{
}

class ModelUsingTraitTranslatable
{
    use Translatable;
}

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class TranslatableCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test TranslatableChecker::isTranslatable
     */
    public function testIsTranslatableOnInterface()
    {
        $translatableChecker = new TranslatableChecker();

        $object = new ModelTranslatable();

        $this->assertFalse($translatableChecker->isTranslatable($object));

        $translatableChecker->setSupportedInterfaces(array(
            'Sonata\TranslationBundle\Model\TranslatableInterface',
        ));

        $this->assertTrue($translatableChecker->isTranslatable($object));
    }

    /**
     * @test TranslatableChecker::isTranslatable
     */
    public function testIsTranslatableOnModel()
    {
        $translatableChecker = new TranslatableChecker();

        $object = new ModelCustomTranslatable();

        $this->assertFalse($translatableChecker->isTranslatable($object));

        $translatableChecker->setSupportedModels(array(
            'Sonata\AdminBundle\Tests\Checker\ModelCustomTranslatable',
        ));

        $this->assertTrue($translatableChecker->isTranslatable($object));
    }

    /**
     * @test TranslatableChecker::isTranslatable
     */
    public function testIsTranslatableOnTrait()
    {
        $translatableChecker = new TranslatableChecker();

        $object = new ModelUsingTraitTranslatable();

        $this->assertTrue($translatableChecker->isTranslatable($object));
    }
}
