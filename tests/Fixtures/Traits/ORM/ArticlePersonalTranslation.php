<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Table(name="article_translation")
 * @ORM\Entity
 */
class ArticlePersonalTranslation extends AbstractPersonalTranslation
{
    /**
     * @var ArticlePersonalTranslatable
     *
     * @ORM\ManyToOne(targetEntity="Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslatable", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
