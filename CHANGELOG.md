# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.1.1](https://github.com/sonata-project/SonataTranslationBundle/compare/2.1.0...2.1.1) - 2017-04-04
### Changed
- use `Sonata\BlockBundle\Block\Service\AbstractBlockService` instead of deprecated `Sonata\BlockBundle\Block\BaseBlockService` in `LocaleSwitcherBlockService`
- renamed `service.xml` to `service_orm.xml`
- only load `service_orm.xml` if `SonataDoctrineORMAdminBundle` is registered

### Fixed
- Fixed typo in `SonataTranslationExtension`

## [2.1.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.0.2...2.1.0) - 2017-01-17
### Added
- Added missing flag image `ja.png`

### Changed
- Use `AbstractAdminExtension` instead of deprecated `AdminExtension`

### Deprecated
- Deprecate `Translatable` in favor of `TranslatableTrait`
- Deprecate `PersonalTranslatable` in favor of `PersonalTranslatableTrait`

### Fixed
- Fix missing locale annotation for Gedmo trait
- Display flags for locales with country code

## [2.0.2](https://github.com/sonata-project/SonataTranslationBundle/compare/2.0.1...2.0.2) - 2016-06-15
### Fixed
- Remove wrong `doctrine-orm-admin-bundle` and `doctrine-phpcr-admin-bundle` composer requirements

## [2.0.1](https://github.com/sonata-project/SonataTranslationBundle/compare/2.0.0...2.0.1) - 2016-06-07
### Fixed
- Fix wrong conflict rule for `doctrine-orm-admin-bundle`
