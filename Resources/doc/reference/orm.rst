Translate Doctrine ORM models
=============================


Doctrine ORM models translations are handled by `Gedmo translatable extension <https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md>`_.

Gedmo have two ways to handle translations.

Either everything is saved in a unique table, this is easier to set up but can lead to bad performance if your project grows or it can have one translation table for every
model table. This second way is called personal translation.


1. Implement TranslatableInterface
----------------------------------

First step, your entities have to implement `TranslatableInterface <https://github.com/sonata-project/SonataTranslationBundle/blob/master/Model/TranslatableInterface.php>`_.

Todo do so SonataTranslationBundle brings some base classes you can extend.
Depends on how you want to save translations you can choose between :

* `Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable`
* `Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable`


**Note:** If your prefer to use `traits`, we provide :

* `Sonata\TranslationBundle\Traits\Translatable`
* `Sonata\TranslationBundle\Traits\PersonalTranslatable`

2. Define translated fields
---------------------------

Plese refer to `Gedmo translatable documentation <https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md>`_.


3. Define your translation table
--------------------------------

Optinal, if you choose personal translation, you have to make a translation class to handle it.
