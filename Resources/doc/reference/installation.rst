Installation
============

The easiest way to install ``SonataTranslationBundle`` is to require it with Composer:

.. code-block:: bash

    $ php composer.phar require sonata-project/translation-bundle

Alternatively, you could add a dependency into your ``composer.json`` file directly.

Now, enable the bundle in the kernel:

.. code-block:: php

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new Sonata\TranslationBundle\SonataTranslationBundle(),
            // ...
        );
    }

Configuration
-------------

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
            #phpcr:
            #    enabled: true

==================      ============================================================================
Key                     Description
==================      ============================================================================
**locales**             The list of your frontend locales in which your models will be translatable.
**default_locale**      The locale, loaded in your forms by default.
==================      ============================================================================

.. note::

    If you are using ``SonatAdmin`` with SonataDoctrineORMAdminBundle_ you should read the :doc:`ORM chapter </reference/orm>`,
    if you are using SonataDoctrinePhpcrAdminBundle_ you should read the :doc:`PHPCR chapter </reference/phpcr>`.

Import the Styles
-----------------

Extend ``SonataAdmin`` layout if it's not already done and add the ``SonataTranslation`` styles like this :

If you are using less/sass use this one :

.. code-block:: jinja

    {% block stylesheets %}
        {{  parent() }}
        {% stylesheets
            '@SonataTranslationBundle/Resources/public/less/sonata-translation.less'
        %}

            <link rel="stylesheet" href="{{ asset_url }}" />
        {% endstylesheets %}
    {% endblock %}

otherwise you could add the compiled css file :

.. code-block:: php

    {% block stylesheets %}
        {{  parent() }}
        {% stylesheets
            '@SonataTranslationBundle/Resources/public/css/sonata-translation.css'
        %}

            <link rel="stylesheet" href="{{ asset_url }}" />
        {% endstylesheets %}
    {% endblock %}

And now, you're good to go !

.. _SonataDoctrineORMAdminBundle: https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/index.html
.. _SonataDoctrinePhpcrAdminBundle: https://sonata-project.org/bundles/doctrine-phpcr-admin/master/doc/index.html
