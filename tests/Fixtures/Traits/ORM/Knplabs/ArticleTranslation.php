<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

// @todo Remove check and else part when dropping support for knplabs/doctrine-behaviors < 2.0
if (interface_exists(TranslationInterface::class)) {
    /**
     * @ORM\Table(name="article_translation")
     * @ORM\Entity
     */
    final class ArticleTranslation extends AbstractArticleTranslation implements TranslationInterface
    {
        use TranslationTrait;

        public static function getTranslatableEntityClass(): string
        {
            return Article::class;
        }
    }
} else {
    /**
     * @ORM\Table(name="article_translation")
     * @ORM\Entity
     */
    final class ArticleTranslation extends AbstractArticleTranslation
    {
        use Translation;

        public static function getTranslatableEntityClass(): string
        {
            return Article::class;
        }
    }
}
