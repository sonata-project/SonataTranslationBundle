UPGRADE FROM 2.x to 3.0
=======================

## Dropped support for PHPCR

This bundle no longer supports `doctrine/phpcr-odm`, the implementation was based on having installed
`sonata-project/doctrine-phpcr-admin-bundle` which has been abandoned and do not support newer versions of
`sonata-project/block-bundle` and `sonata-project/admin-bundle`.

## Deprecations

All the deprecated code introduced on 3.x is removed on 4.0.

Please read [2.x](UPGRADE-2.x.md) upgrade guides for more information.

See also the [diff code](https://github.com/sonata-project/SonataTranslationBundle/compare/2.x...3.0.0).
