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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;

#[ORM\Table(name: 'article')]
#[ORM\Entity]
class Article extends TranslatableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\OneToMany(
        targetEntity: ArticleTranslation::class,
        mappedBy: 'translatable',
        orphanRemoval: true,
        cascade: ['persist', 'merge', 'remove'],
        indexBy: 'locale'
    )]
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->newTranslations = new ArrayCollection();
    }

    public static function getTranslationEntityClass(): string
    {
        return ArticleTranslation::class;
    }
}
