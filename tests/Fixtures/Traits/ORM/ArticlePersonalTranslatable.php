<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslation;

/**
 * @Gedmo\TranslationEntity(class="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslation")
 * @ORM\Table(name="article")
 * @ORM\Entity
 */
class ArticlePersonalTranslatable
{
    use PersonalTranslatableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @Gedmo\Translatable
     * @ORM\Column(length=128)
     */
    private $title;

    /**
     * @var ArrayCollection<array-key, AbstractPersonalTranslation>
     *
     * @ORM\OneToMany(
     *     targetEntity="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
