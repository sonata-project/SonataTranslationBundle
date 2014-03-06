Translate PHPCR models
======================


Doctrine PHPCR ODM handles translations natively so you don't need to install anything else.


1. Models
---------

First step is to defined which fields you want to translate.

Please refer to the `Doctrine PHPCR ODM documentation <http://doctrine-phpcr-odm.readthedocs.org/en/latest/reference/multilang.html>`_

Then your model should just implement `Sonata\TranslationBundle\Model\Phpcr\TranslatableInterface`.
This can be done easily by using `Sonata\TranslationBundle\Traits\Translatable`.

2. Configuration
----------------

If you already followed Installation Step 3, you just have to pay attention to PHPCR fallback configuration.

So if you configured SonataTranslation like this :

.. code-block:: yaml

    sonata_translation:
        locales: [fr, en, it, nl, es]

Then you should configure `doctrine_phpcr.odm.locales` for the same list.
