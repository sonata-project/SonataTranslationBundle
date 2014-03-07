Translate Doctrine ORM models
=============================


Doctrine ORM models translations are handled by `Gedmo translatable extension <https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md>`_.

Gedmo have two ways to handle translations.

Either everything is saved in a unique table, this is easier to set up but can lead to bad performance if your project
grows or it can have one translation table for every model table. This second way is called personal translation.


1. Implement TranslatableInterface
----------------------------------

First step, your entities have to implement `TranslatableInterface <https://github.com/sonata-project/SonataTranslationBundle/blob/master/Model/Gedmo/TranslatableInterface.php>`_.

Todo do so SonataTranslationBundle brings some base classes you can extend.
Depends on how you want to save translations you can choose between :

* `Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable`
* `Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable`

**Here is an example of an entity using Personal Translation :**

.. code-block:: php

    namespace Presta\CMSFAQBundle\Entity;

    use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
    use Gedmo\Mapping\Annotation as Gedmo;
    use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;

    /**
     * @ORM\Table(name="presta_cms_faq_category")
     * @ORM\Entity(repositoryClass="Presta\CMSFAQBundle\Entity\FAQCategory\Repository")
     * @Gedmo\TranslationEntity(class="Presta\CMSFAQBundle\Entity\FAQCategory\Translation")
     */
    class FAQCategory extends AbstractPersonalTranslatable implements TranslatableInterface
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @var string $title
         *
         * @Gedmo\Translatable
         * @ORM\Column(name="title", type="string", length=255, nullable=true)
         */
        private $title;

        /**
         * @var boolean $enabled
         *
         * @ORM\Column(name="enabled", type="boolean", nullable=false)
         */
        private $enabled = false;

        /**
         * @var integer $position
         *
         * @ORM\Column(name="position", type="integer", length=2, nullable=true)
         */
        private $position;

        /**
         * @var ArrayCollection
         *
         * @ORM\OneToMany(
         *     targetEntity="Presta\CMSFAQBundle\Entity\FAQCategory\Translation",
         *     mappedBy="object",
         *     cascade={"persist", "remove"}
         * )
         */
        protected $translations;

        /* ... */
    }

**Note:** If your prefer to use `traits`, we provide :

* `Sonata\TranslationBundle\Traits\Translatable`
* `Sonata\TranslationBundle\Traits\PersonalTranslatable`


**Here is the same class using traits :**

.. code-block:: php

    namespace Presta\CMSFAQBundle\Entity;

    use Gedmo\Mapping\Annotation as Gedmo;
    use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatable;

    /**
     * @author Nicolas Bastien <nbastien@prestaconcept.net>
     *
     * @ORM\Table(name="presta_cms_faq_category")
     * @ORM\Entity(repositoryClass="Presta\CMSFAQBundle\Entity\FAQCategory\Repository")
     * @Gedmo\TranslationEntity(class="Presta\CMSFAQBundle\Entity\FAQCategory\Translation")
     */
    class FAQCategory  implements TranslatableInterface
    {
        use PersonalTranslatable;

        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /* ... */
    }


2. Define translated fields
---------------------------

Please refer to `Gedmo translatable documentation <https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md>`_.


3. Define your translation table
--------------------------------

Optinal, if you choose personal translation, you have to make a translation class to handle it.

**Here is the personal translation class for the example above :**

.. code-block:: php

    namespace Presta\CMSFAQBundle\Entity\FAQCategory;

    use Doctrine\ORM\Mapping as ORM;
    use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

    /**
     * @ORM\Entity
     * @ORM\Table(name="presta_cms_faq_category_translation",
     *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_faq_category_translation_idx", columns={
     *         "locale", "object_id", "field"
     *     })}
     * )
     */
    class Translation extends AbstractPersonalTranslation
    {
        /**
         * @ORM\ManyToOne(targetEntity="Presta\CMSFAQBundle\Entity\FAQCategory", inversedBy="translations")
         * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
         */
        protected $object;
    }
