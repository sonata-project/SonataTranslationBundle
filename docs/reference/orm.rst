=============================
Translate Doctrine ORM models
=============================

You can either use :ref:`Gedmo Doctrine Extensions <gedmo_doctrine_extensions>` or
:ref:`KnpLabs Doctrine Behaviors <knp_labs_doctrine_bahaviors>`.

.. _gedmo_doctrine_extensions:

Using Gedmo Doctrine Extensions
===============================

Doctrine ORM models translations are handled by `Gedmo translatable extension`_.

Gedmo has two ways to handle translations.

Either everything is saved in a unique table, this is easier to set up but can lead to bad performance if your project
grows or it can have one translation table for every model table. This second way is called personal translation.

Implement Translatable
----------------------

First step, your entities have to implement `Gedmo\Translatable\Translatable`.

Define translatable Fields
--------------------------

Please check the docs in the `Gedmo translatable documentation`_.

Example using Personal Translation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    // src/Entity/FAQCategory.php

    namespace Presta\CMSFAQBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Gedmo\Mapping\Annotation as Gedmo;
    use Gedmo\Translatable\Translatable;

    /**
     * @ORM\Table(name="presta_cms_faq_category")
     * @ORM\Entity(repositoryClass="Presta\CMSFAQBundle\Entity\FAQCategory\Repository")
     * @Gedmo\TranslationEntity(class="Presta\CMSFAQBundle\Entity\FAQCategory\Translation")
     */
    class FAQCategory implements Translatable
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;

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

Define your translation Table
-----------------------------

**This step is optional**, but if you choose Personal Translation,
you have to make a translation class to handle it.

Example for translation class for Personal Translation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    // src/Entity/FAQCategory/Translation.php

    namespace Presta\CMSFAQBundle\Entity\FAQCategory;

    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

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

**This step is optional**, but you can use the ``TranslationFieldFilter::class``
filter to search on fields and on their translations. Depending on whether you choose to use **KnpLabs** or **Gedmo**,
you should configure the ``default_filter_mode`` in the configuration. You can also configure how
the filtering logic should work on a per-field basis by specifying an option named ``filter_mode`` on your field.
An enumeration exposes the two supported modes: ``TranslationFilterMode::GEDMO`` and ``TranslationFilterMode::KNPLABS``

Example for configure search filter
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    namespace App\Admin;

    use Sonata\AdminBundle\Admin\AbstractAdmin;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;
    use Sonata\TranslationBundle\Filter\TranslationFieldFilter;
    use Sonata\TranslationBundle\Enum\TranslationFilterMode;

    final class FAQCategoryAdmin extends AbstractAdmin
    {
        protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
        {
            $datagridMapper
                ->add('title', TranslationFieldFilter::class, [
                    // if not specified, it will default to the value
                    // you set in `default_filter_mode`
                    'filter_mode' => TranslationFilterMode::KNPLABS
                ]);
        }

.. _knp_labs_doctrine_bahaviors:

Using KnpLabs Doctrine Behaviors
================================

Due to Sonata internals, the `magic method <https://github.com/KnpLabs/DoctrineBehaviors#proxy-translations>`_
of Doctrine Behavior does not work. For more background on that topic, see this
`post <https://web.archive.org/web/20150224121239/http://thewebmason.com/tutorial-using-sonata-admin-with-magic-__call-method/>`_::

    // src/Entity/TranslatableEntity.php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
    use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

    /**
     * @ORM\Table(name="app_translatable_entity")
     * @ORM\Entity()
     */
    class TranslatableEntity implements TranslatableInterface
    {
        use TranslatableTrait;

        /**
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private int $id;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $nonTranslatedField;

        public function getId(): int
        {
            return $this->id;
        }

        public function getNonTranslatableField(): string
        {
            return $this->nonTranslatedField;
        }

        public function setNonTranslatableField(string $nonTranslatedField): TranslatableEntity
        {
            $this->nonTranslatedField = $nonTranslatedField;

            return $this;
        }

        public function getName(): string
        {
            return $this->translate(null, false)->getName();
        }

        public function setName(string $name): TranslatableEntity
        {
            $this->translate(null, false)->setName($name);

            return $this;
        }
    }

Define your translation table
-----------------------------

Please refer to `KnpLabs Doctrine2 Behaviors Documentation <https://github.com/KnpLabs/DoctrineBehaviors/blob/master/docs/translatable.md>`_.

Here is an example::

    // src/Entity/TranslatableEntityTranslation.php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
    use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

    /**
     * @ORM\Entity
     */
    class TranslatableEntityTranslation implements TranslationInterface
    {
        use TranslationTrait;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $name;

        public function getId(): int
        {
            return $this->id;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function setName(string $name): TranslatableEntityTranslation
        {
            $this->name = $name;

            return $this;
        }
    }

.. _Gedmo translatable extension: https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md
.. _Gedmo translatable documentation: https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md
