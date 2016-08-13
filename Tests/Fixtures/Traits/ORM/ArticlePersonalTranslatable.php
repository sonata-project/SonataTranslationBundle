<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;

/**
 * @Gedmo\TranslationEntity(class="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslation")
 * @ORM\Table(name="article")
 * @ORM\Entity
 */
class ArticlePersonalTranslatable
{
    use PersonalTranslatableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(length=128)
     */
    private $title;

    /**
     * @var ArrayCollection
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

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
