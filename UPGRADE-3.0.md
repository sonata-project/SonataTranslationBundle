UPGRADE FROM 2.x to 3.0
=======================

## Removed `Sonata\TranslationBundle\EventSubscriber\UserLocaleSubscriber`

In case you need to set the locale based on the user's preferences, you MUST implement your own event
listener, for that you can follow https://symfony.com/index.php/doc/4.4/session/locale_sticky_session.html#setting-the-locale-based-on-the-user-s-preferences

## Dropped support for PHPCR

This bundle no longer supports `doctrine/phpcr-odm`, the implementation was based on having installed
`sonata-project/doctrine-phpcr-admin-bundle` which has been abandoned and do not support newer versions of
`sonata-project/block-bundle` and `sonata-project/admin-bundle`.

## Deprecations

All the deprecated code introduced on 3.x is removed on 4.0.

Please read [2.x](UPGRADE-2.x.md) upgrade guides for more information.

See also the [diff code](https://github.com/sonata-project/SonataTranslationBundle/compare/2.x...3.0.0).
