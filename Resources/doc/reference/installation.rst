Installation
============

* Add SonataTranslationBundle to your vendor/bundles dir with the deps file:

.. code-block:: php

    //composer.json
    "require": {
    //...
        "sonata-project/translation-bundle": "dev-master",
    //...
    }


* Add SonataTranslationBundle to your application kernel:

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

* Create a configuration file : ``sonata_translation.yml``:

.. code-block:: yaml

    sonata_translation:
        # ...



* import the ``sonata_translation.yml`` file and enable json type for doctrine:

.. code-block:: yaml

    imports:
        #...
        - { resource: sonata_translation.yml }

