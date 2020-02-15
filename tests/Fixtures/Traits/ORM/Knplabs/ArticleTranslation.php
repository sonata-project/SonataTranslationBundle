<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

/**
 * @ORM\Table(name="article_translation")
 * @ORM\Entity
 */
final class ArticleTranslation
{
    use Translation;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(
     *     targetEntity="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs\ArticleTranslation",
     *     inversedBy="translations",
     *     cascade={"persist", "merge"}
     * )
     * @ORM\JoinColumn(name="translatable_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $translatable;

    /**
     * @ORM\Column(length=128)
     */
    protected $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public static function getTranslatableEntityClass(): string
    {
        return Article::class;
    }
}
