Installation
============

Download the Bundle
-------------------

.. code-block:: bash

    composer require sonata-project/translation-bundle

Enable the Bundle
-----------------

Then, enable the bundle by adding it to the list of registered bundles
in ``bundles.php`` file of your project::

    // config/bundles.php

    return [
        // ...
        Sonata\TranslationBundle\SonataTranslationBundle::class => ['all' => true],
    ];

Configure the Bundle
--------------------

To use the ``TranslationBundle``, add the following lines to your application configuration file:

.. code-block:: yaml

    # config/packages/sonata_translation.yaml

    sonata_translation:
        locales: [en, fr, it, nl, es]
        default_locale: en
        # change default behavior for translated field filtering.
        default_filter_mode: gedmo # must be either 'gedmo' or 'knplabs', default: gedmo
        # here enable the types you need
        gedmo:
            enabled: true
            # when using gedmo/doctrine-extensions, you have to register a translatable listener
            # service or if you are using a bundle that integrates the library, it will be registered
            # by the bundle (e.g. "stof_doctrine_extensions.listener.translatable" for "stof/doctrine-extensions-bundle").
            # here you can provide a custom translatable listener service name.
            translatable_listener_service: Gedmo\Translatable\TranslatableListener
        knplabs:
            enabled: true

    sonata_block:
        blocks:
            sonata_translation.block.locale_switcher:

==================  ============================================================================
Key                 Description
==================  ============================================================================
**locales**         The list of your frontend locales in which your models will be translatable.
**default_locale**  The locale, loaded in your forms by default.
==================  ============================================================================

.. note::

    If you are using the SonatAdminBundle with the SonataDoctrineORMAdminBundle_, you should
    read the :doc:`ORM chapter </reference/orm>`.

Import the Styles
-----------------

Install SonataTranslationBundle web assets under your public web directory:

.. code-block:: bash

    bin/console assets:install

Add CSS file to your SonataAdminBundle config:

.. code-block:: yaml

    # config/packages/sonata_admin.yaml

    sonata_admin:
        assets:
            extra_stylesheets:
                - bundles/sonatatranslation/css/sonata-translation.css

Now, you're good to go!

.. _installation chapter: https://getcomposer.org/doc/00-intro.md
.. _SonataDoctrineORMAdminBundle: https://docs.sonata-project.org/projects/SonataDoctrineORMAdminBundle/en/3.x/
