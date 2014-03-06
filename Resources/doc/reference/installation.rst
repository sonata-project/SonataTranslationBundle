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

    sonata_translation:
        locales: [fr, en, it, nl, es]
        default_locale: fr


* **locales**: is the list of your frontend locales ie the locales in which your models will be translated.
* **default_locale**: is simply the locale loaded by default in your forms


4. import the ``sonata_translation.yml`` file and enable json type for doctrine:

.. code-block:: yaml

    imports:
        #...
        - { resource: bundles/sonata_translation.yml }

