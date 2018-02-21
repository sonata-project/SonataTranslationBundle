Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require sonata-project/translation-bundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in ``bundles.php`` file of your project:

.. code-block:: php

    <?php

    // config/bundles.php

    return [
        //...
        Sonata\TranslationBundle\SonataTranslationBundle::class => ['all' => true],
    ];

.. note::
    If you are not using Symfony Flex, you should enable bundle in your
    ``AppKernel.php``.

.. code-block:: php

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Sonata\TranslationBundle\SonataTranslationBundle(),
            );

            // ...
        }

        // ...
    }

Step 3: Configure the Bundle
----------------------------

To use the ``TranslationBundle``, add the following lines to your application configuration file:

.. configuration-block::

    .. code-block:: yaml

        # config/packages/sonata.yaml

        sonata_translation:
            locales: [en, fr, it, nl, es]
            default_locale: en
            # here enable the types you need
            gedmo:
                enabled: true
            knplabs:
                enabled: true
            #phpcr:
            #    enabled: true

==================  ============================================================================
Key                 Description
==================  ============================================================================
**locales**         The list of your frontend locales in which your models will be translatable.
**default_locale**  The locale, loaded in your forms by default.
==================  ============================================================================

.. note::
    If you are not using Symfony Flex, this configuration should be added
    to ``app/config/config.yml``.

.. note::

    If you are using the SonatAdminBundle with the SonataDoctrineORMAdminBundle_, you should
    read the :doc:`ORM chapter </reference/orm>`. If you are using SonataDoctrinePhpcrAdminBundle_,
    you should read the :doc:`PHPCR chapter </reference/phpcr>`.

Step 4: Import the Styles
-------------------------

Install SonataTranslationBundle web assets under your public web directory:

.. code-block:: bash

    $  bin/console assets:install

Add CSS file to your SonataAdminBundle config:

.. code-block:: yaml

    # config/packages/sonata_admin.yaml

    sonata_admin:
        # ...
        assets:
            extra_stylesheets:
                - bundles/sonatatranslation/css/sonata-translation.css

.. note::
    If you are not using Symfony Flex, this configuration should be added
    to ``app/config/config.yml``.

Now, you're good to go!

.. _installation chapter: https://getcomposer.org/doc/00-intro.md
.. _SonataDoctrineORMAdminBundle: https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/index.html
.. _SonataDoctrinePhpcrAdminBundle: https://sonata-project.org/bundles/doctrine-phpcr-admin/master/doc/index.html
