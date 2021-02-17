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
use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;

/**
 * @ORM\Table(name="article")
 * @ORM\Entity
 */
final class Article extends TranslatableEntity
{
    /**
     * @ORM\OneToMany(
     *     indexBy="locale",
     *     targetEntity="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs\ArticleTranslation",
     *     mappedBy="translatable",
     *     orphanRemoval=true,
     *     cascade={"persist", "merge", "remove"}
     * )
     */
    protected $translations;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public static function getTranslationEntityClass(): string
    {
        return ArticleTranslation::class;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
