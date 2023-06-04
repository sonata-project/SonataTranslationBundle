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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractArticleTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected ?int $id = null;

    /**
     * @var Article|null
     */
    #[ORM\ManyToOne(
        targetEntity: ArticleTranslation::class,
        inversedBy: 'translations',
        cascade: ['persist', 'merge'],
    )]
    #[ORM\JoinColumn(name: 'translatable_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected $translatable;

    #[ORM\Column(length: 128)]
    protected ?string $title = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
