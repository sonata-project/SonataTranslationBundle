Translate PHPCR models
======================

Doctrine PHPCR ODM handles translations natively so you don't need to install anything else.

Implement the TranslatableInterface
-----------------------------------

Todo do so ``SonataTranslationBundle`` brings the ``Sonata\TranslationBundle\Model\Phpcr\TranslatableInterface`` you can implement.

Define translatable Fields
--------------------------

Please check the `Doctrine PHPCR ODM documentation`_ how you can define your fields you want to translate.

Advanced Configuration
----------------------

If you already followed :doc:`Installation </reference/installation>`, you just have to pay attention to PHPCR fallback configuration.

So if you configured ``SonataTranslation`` like this :

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml

        sonata_translation:
            locales: [fr, en, it, nl, es]

Then you should configure ``doctrine_phpcr.odm.locales`` for the same list.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml

        doctrine_phpcr:
            odm:
                locales:
                    fr: [en, it]
                    en: []
                    it: [fr, en]
                    nl: [en]
                    es: [en, fr, it]

.. _Doctrine PHPCR ODM documentation: http://doctrine-phpcr-odm.readthedocs.org/en/latest/reference/multilang.html
