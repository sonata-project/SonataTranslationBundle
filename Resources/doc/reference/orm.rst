=============================
Translate Doctrine ORM models
=============================

You can either use :ref:`Gedmo Doctrine Extensions <gedmo_doctrine_extensions>` or :ref:`KnpLabs Doctrine Behaviours <knp_labs_doctrine_bahaviours>`.

.. _gedmo_doctrine_extensions:

Using Gedmo Doctrine Extensions
===============================

Doctrine ORM models translations are handled by `Gedmo translatable extension`_.

Gedmo has two ways to handle translations.

Either everything is saved in a unique table, this is easier to set up but can lead to bad performance if your project
grows or it can have one translation table for every model table. This second way is called personal translation.

Implement the TranslatableInterface
-----------------------------------

First step, your entities have to implement the `TranslatableInterface`_.

Todo do so ``SonataTranslationBundle`` brings some base classes you can extend.
Depends on how you want to save translations you can choose between :

* `Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable`
* `Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable`

Define translatable Fields
--------------------------

Please check the docs in the `Gedmo translatable documentation`_.

Example using Personal Translation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
    // src/AppBundle/Entity/FAQCategory.php

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

        // ...
    }

.. note::

    If you prefer to use `traits`, we provide :

    * `Sonata\TranslationBundle\Traits\TranslatableTrait`
    * `Sonata\TranslationBundle\Traits\PersonalTranslatableTrait`

Example using Personal Translation with Traits
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
    // src/AppBundle/Entity/FAQCategory.php

    namespace Presta\CMSFAQBundle\Entity;

    use Gedmo\Mapping\Annotation as Gedmo;
    use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;

    /**
     * @author Nicolas Bastien <nbastien@prestaconcept.net>
     *
     * @ORM\Table(name="presta_cms_faq_category")
     * @ORM\Entity(repositoryClass="Presta\CMSFAQBundle\Entity\FAQCategory\Repository")
     * @Gedmo\TranslationEntity(class="Presta\CMSFAQBundle\Entity\FAQCategory\Translation")
     */
    class FAQCategory implements TranslatableInterface
    {
        use PersonalTranslatableTrait;

        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        // ...
    }

Define your translation Table
-----------------------------

**This step is optional**, but if you choose Personal Translation,
you have to make a translation class to handle it.

Example for translation class for Personal Translation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
    // src/AppBundle/Entity/FAQCategory/Translation.php

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

Configure search filter
-----------------------

**This step is optional**, but you can use the ``doctrine_orm_translation_field``
filter to search on fields and on their translations.

Example for configure search filter
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php

    namespace AppBundle\Admin;

    use Sonata\AdminBundle\Admin\AbstractAdmin;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;
    use Sonata\AdminBundle\Datagrid\ListMapper;
    use Sonata\AdminBundle\Form\FormMapper;
    use Sonata\AdminBundle\Show\ShowMapper;
    use Sonata\TranslationBundle\Filter\TranslationFieldFilter;

    class FAQCategoryAdmin extends AbstractAdmin
    {
        /**
         * @param DatagridMapper $datagridMapper
         */
        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagridMapper
                // ...
                ->add('title', TranslationFieldFilter::class); // or 'doctrine_orm_translation_field'
        }

.. _knp_labs_doctrine_bahaviours:

Using KnpLabs Doctrine Behaviours
=================================

Implement TranslatableInterface
-------------------------------

Your entities have to implement `Model\TranslatableInterface <https://github.com/sonata-project/SonataTranslationBundle/blob/master/Model/TranslatableInterface.php>`_.

Your entities need to explicitly implement getter and setter methods for the knp doctrine extensions. Due to Sonata internals, the `magic method <https://github.com/KnpLabs/DoctrineBehaviors#proxy-translations>`_ of Doctrine Behaviour does not work. For more background on that topic, see this `post <http://thewebmason.com/tutorial-using-sonata-admin-with-magic-__call-method/>`_

.. code-block:: php

    <?php
    // src/AppBundle/Entity/TranslatableEntity.php

    namespace AppBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Knp\DoctrineBehaviors\Model as ORMBehaviors;
    use Sonata\TranslationBundle\Model\TranslatableInterface;

    /**
     * @ORM\Table(name="app_translatable_entity")
     * @ORM\Entity()
     */
    class TranslatableEntity implements TranslatableInterface
    {
        use ORMBehaviors\Translatable\Translatable;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(type="string", length=255)
         */
        private $nonTranslatedField;

        /**
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getNonTranslatableField()
        {
            return $this->nonTranslatedField;
        }

        /**
         * @param string $nonTranslatedField
         *
         * @return TranslatableEntity
         */
        public function setNonTranslatableField($nonTranslatedField)
        {
            $this->nonTranslatedField = $nonTranslatedField;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->translate(null, false)->getName();
        }

        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->translate(null, false)->setName($name);

            return $this;
        }

        /**
         * @param string $locale
         */
        public function setLocale($locale)
        {
            $this->setCurrentLocale($locale);

            return $this;
        }

        /**
         * @return string
         */
        public function getLocale()
        {
            return $this->getCurrentLocale();
        }
    }


Define your translation table
-----------------------------

Please refer to `KnpLabs Doctrine2 Behaviors Documentation <https://github.com/KnpLabs/DoctrineBehaviors#translatable>`_.

Here is an example:

.. code-block:: php

    <?php
    // src/AppBundle/Entity/TranslatableEntityTranslation.php

    namespace AppBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Knp\DoctrineBehaviors\Model as ORMBehaviors;

    /**
     * @ORM\Table(name="app_translatable_entity_translation")
     * @ORM\Entity
     */
    class TranslatableEntityTranslation
    {
        use ORMBehaviors\Translatable\Translation;

        /**
         * @var string
         *
         * @ORM\Column(type="string", length=255)
         */
        private $name;

        /**
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param string $name
         *
         * @return TranslatableEntityTranslation
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }
    }

.. _Gedmo translatable extension: https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md
.. _Gedmo translatable documentation: https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md
.. _TranslatableInterface: https://github.com/sonata-project/SonataTranslationBundle/blob/master/Model/Gedmo/TranslatableInterface.php
