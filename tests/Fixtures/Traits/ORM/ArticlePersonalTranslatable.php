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

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @Gedmo\TranslationEntity(class="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslation")
 * @ORM\Table(name="article")
 * @ORM\Entity
 */
class ArticlePersonalTranslatable
{
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

    /**
     * @Gedmo\Locale
     *
     * @var string|null
     */
    protected $locale;

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

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string|null
     */
    public function getLocale()
    {
        return $this->locale;
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

    /**
     * @return ArrayCollection
     *
     * @phpstan-return ArrayCollection<array-key, AbstractPersonalTranslation>
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    public function getTranslation(string $field, string $locale): ?string
    {
        foreach ($this->getTranslations() as $translation) {
            if (0 === strcmp($translation->getField(), $field) && 0 === strcmp($translation->getLocale(), $locale)) {
                return $translation->getContent();
            }
        }

        return null;
    }

    public function addTranslation(AbstractPersonalTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }

        return $this;
    }
}
