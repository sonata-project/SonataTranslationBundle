Installation
============

1. Add SonataTranslationBundle to your vendor/bundles dir via composer:

.. code-block:: php

    //composer.json
    "require": {
    //...
        "sonata-project/translation-bundle": "2.3.*",
    //...
    }


2. Add SonataTranslationBundle to your application kernel:

.. code-block:: php

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Sonata\TranslationBundle\SonataTranslationBundle(),
            // ...
        );
    }

3. Create a configuration file : ``bundles/sonata_translation.yml``:

.. code-block:: yaml

    imports:
        - { resource: '@SonataTranslationBundle/Resources/config/config.yml'}

    sonata_translation:
        locales: [fr, en, it, nl, es]
        default_locale: fr
        # here enable the types you need
        gedmo:
            enabled: true
        phpcr:
            enabled: true

* **locales**: is the list of your frontend locales ie the locales in which your models will be translated.
* **default_locale**: is simply the locale loaded by default in your forms

**Advanced configuration** :

By default SonataTranslation provides a set of default interfaces you should implement to see your models
automatically handled.
If you have specific needs and can't use them, this bundle gives you other ways to use it.

Here is an example with phpcr :

.. code-block:: yaml

    sonata_translation:
        phpcr:
            enabled: true
            implements:
                - # list your custom interfaces here
                - MyProject\MyBundle\Model\CustomTranslatableInterface
            instanceof:
                - # list your specific models or abstract classes here
                - MyProject\MyBundle\Model\AbstractCustomTranslatable


4. Import the ``sonata_translation.yml`` file in `app/config.yml`:

.. code-block:: yaml

    imports:
        #...
        - { resource: bundles/sonata_translation.yml }

5. Import styles

Extends SonataAdmin layout if it's not already done and add sonata translation styles like this :

.. code-block:: jinja

    {% block stylesheets %}
        {{  parent() }}
        {% stylesheets
            '@SonataTranslationBundle/Resources/public/less/sonata-translation.less'
        %}

        <link rel="stylesheet" href="{{ asset_url }}" />
        {% endstylesheets %}
    {% endblock %}

**Note** To help users without less, we made a compiled css : `'@SonataTranslationBundle/Resources/public/css/sonata-translation.css'`

