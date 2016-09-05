UPGRADE 2.x
===========

### Tests

All files under the ``Tests`` directory are now correctly handled as internal test classes. 
You can't extend them anymore, because they are only loaded when running internal tests. 
More information can be found in the [composer docs](https://getcomposer.org/doc/04-schema.md#autoload-dev).

## Deprecated traits

The `Sonata\TranslationBundle\Traits\Translatable` class is deprecated. 
Use `Sonata\TranslationBundle\Traits\TranslatableTrait` instead.

The `Sonata\TranslationBundle\Traits\PersonalTranslatable` class is deprecated. 
Use `Sonata\TranslationBundle\Traits\PersonalTranslatableTrait` instead.
