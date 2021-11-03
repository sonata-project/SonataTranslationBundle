UPGRADE 2.x
===========

UPGRADE FROM 2.8 to 2.9
=======================

### Deprecated abstract models and traits

- `Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable`.
- `Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation`.
- `Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable`.
- `Sonata\TranslationBundle\Model\AbstractTranslatable`.
- `Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait`.
- `Sonata\TranslationBundle\Traits\Gedmo\TranslatableTrait`.
- `Sonata\TranslationBundle\Traits\TranslatableTrait`.

All abstract models and traits for translatable models have been deprecated without replacement, you should create
your own.

### Deprecated implementing `Sonata\TranslationBundle\Model\TranslatableInterface` with `knplabs/doctrine-behaviors`

It is deprecated implementing `Sonata\TranslationBundle\Model\TranslatableInterface` when using
`knplabs/doctrine-behaviors` >= 3

### Deprecated constructing admin translatable extensions without an instance of `LocaleProviderInterface`

`Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension` and
`Sonata\TranslationBundle\Admin\Extension\Phpcr\TranslatableAdminExtension` classes must receive an instance of
`LocaleProviderInterface` as second argument.

### Deprecated `Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface`

When using `gedmo/doctrine-extensions` you MUST implement `Gedmo\Translatable\Translatable` instead.

### Deprecated `Sonata\TranslationBundle\Model\TranslatableInterface`

This interface has been deprecated without replacement, you MUST implement the specific interface based on the
translation package you are using:

- knplabs: `Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface`
- gedmo: `Gedmo\Translatable\Translatable`

UPGRADE FROM 2.7 to 2.8
=======================

If you are using this bundle with "gedmo/doctrine-extensions", you MUST to specify the translatable listener service
name in the configuration.

Before:
```yaml
# config/packages/sonata_translation.yaml

sonata_translation:
    gedmo:
      enabled: true
```
After:
```yaml
# config/packages/sonata_translation.yaml

sonata_translation:
    gedmo:
        enabled: true
        # in case you are using stof/doctrine-extensions-bundle
        translatable_listener_service: stof_doctrine_extensions.listener.translatable
```

UPGRADE FROM 2.1 to 2.7
=======================

### Added support for sonata-project/block-bundle ^4.0

If you are using `sonata-project/block-bundle`, you MUST declare explicitly the dependency with this package on your `composer.json` in order to avoid unwanted upgrades.

There is a minimal BC break on `LocaleSwitcherBlockService`. If you are extending this class (keep in mind that
it's marked as final and cannot be extended on 4.0) you SHOULD add return type declarations to `execute()` and `configureSettings()`.

UPGRADE FROM 2.0 to 2.1
=======================

### Tests

All files under the ``Tests`` directory are now correctly handled as internal test classes.
You can't extend them anymore, because they are only loaded when running internal tests.
More information can be found in the [composer docs](https://getcomposer.org/doc/04-schema.md#autoload-dev).

## Deprecated traits

The `Sonata\TranslationBundle\Traits\Translatable` class is deprecated.
Use `Sonata\TranslationBundle\Traits\TranslatableTrait` instead.

The `Sonata\TranslationBundle\Traits\PersonalTranslatable` class is deprecated.
Use `Sonata\TranslationBundle\Traits\PersonalTranslatableTrait` instead.
