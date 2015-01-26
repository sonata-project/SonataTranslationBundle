Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require sonata-project/translation-bundle "~1"

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the ``app/AppKernel.php`` file of your project:

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

        # app/config/config.yml

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

    If you are using the SonatAdminBundle with the SonataDoctrineORMAdminBundle_, you should
    read the :doc:`ORM chapter </reference/orm>`. If you are using SonataDoctrinePhpcrAdminBundle_,
    you should read the :doc:`PHPCR chapter </reference/phpcr>`.

Step 4: Import the Styles
-------------------------

Extend the `SonataAdminBundle layout`_ and add the SonataTranslationBundle stylesheet like this:

.. code-block:: html+jinja

    {# app/Resources/views/admin/layout.html.twig #}
    {% extends 'SonataAdminBundle::standard_layout.html.twig' %}

    {% block stylesheets %}
        {{  parent() }}

        {# If you're using less, you can also use the
           '@SonataTranslationBundle/Resources/public/less/sonata-translation.less' template #}
        {% stylesheets
            '@SonataTranslationBundle/Resources/public/css/sonata-translation.css'
        %}
            <link rel="stylesheet" href="{{ asset_url }}" />
        {% endstylesheets %}
    {% endblock %}

.. code-block:: yaml

    # app/config/config.yml
    sonata_admin:
        templates:
            layout: admin/layout.html.twig
        # ...

Now, you're good to go!

.. _installation chapter: https://getcomposer.org/doc/00-intro.md
.. _SonataDoctrineORMAdminBundle: https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/index.html
.. _SonataDoctrinePhpcrAdminBundle: https://sonata-project.org/bundles/doctrine-phpcr-admin/master/doc/index.html
.. _SonataAdminBundle layout: https://sonata-project.org/bundles/admin/master/doc/reference/templates.html#configuring-templates
