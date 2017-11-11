<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits;

use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\TranslatableTrait;

class ModelTranslatable implements TranslatableInterface
{
    use TranslatableTrait;
}
