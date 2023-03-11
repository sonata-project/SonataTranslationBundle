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
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[Gedmo\TranslationEntity(class: ArticlePersonalTranslation::class)]
#[ORM\Table(name: 'article')]
#[ORM\Entity]
class ArticlePersonalTranslatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[Gedmo\Locale]
    public ?string $locale = null;

    /**
     * @var Collection<array-key, ArticlePersonalTranslation>
     */
    #[ORM\OneToMany(
        targetEntity: ArticlePersonalTranslation::class,
        mappedBy: 'object',
        cascade: ['persist', 'remove'],
    )]
    protected Collection $translations;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 128)]
    private ?string $title = null;

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

    /**
     * @phpstan-return Collection<array-key, ArticlePersonalTranslation>
     */
    public function getTranslations(): Collection
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

    public function addTranslation(ArticlePersonalTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }

        return $this;
    }
}
