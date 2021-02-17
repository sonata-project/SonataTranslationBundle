<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;

/**
 * @ORM\Table(name="article")
 * @ORM\Entity
 */
final class Article extends TranslatableEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @var TranslationInterface[]|Collection<array-key, TranslationInterface>
     *
     * @ORM\OneToMany(
     *     indexBy="locale",
     *     targetEntity="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs\ArticleTranslation",
     *     mappedBy="translatable",
     *     orphanRemoval=true,
     *     cascade={"persist", "merge", "remove"}
     * )
     */
    protected $translations;

    public static function getTranslationEntityClass(): string
    {
        return ArticleTranslation::class;
    }

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }
}
