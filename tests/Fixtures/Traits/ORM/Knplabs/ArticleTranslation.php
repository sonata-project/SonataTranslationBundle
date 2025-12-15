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

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

#[ORM\Table(name: 'article_translation')]
#[ORM\Entity]
class ArticleTranslation extends AbstractArticleTranslation implements TranslationInterface
{
    use TranslationTrait;

    public static function getTranslatableEntityClass(): string
    {
        return Article::class;
    }
}
