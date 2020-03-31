# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.5.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.4.2...2.5.0) - 2020-03-31
### Added
- Added a global option `default_filter_mode` to change every filter mode
- Added `filter_mode` option for `TranslationFieldFilter` to change the
  filtering mode based on the provider (either `knplabs` or `gedmo`)

### Fixed
- Check if method `getLocale` exists before to use it in `UserLocaleListener`
- Fixed issue caused by always using the default entity manager

### Removed
- Removed `SonataCoreBundle`
- Support for Symfony < 3.4
- Support for Symfony >= 4, < 4.2

## [2.4.2](https://github.com/sonata-project/SonataTranslationBundle/compare/2.4.1...2.4.2) - 2019-07-05
### Fixed
- Admin without entity shouldn't be restricted

## [2.4.1](https://github.com/sonata-project/SonataTranslationBundle/compare/2.4.0...2.4.1) - 2019-02-28
### Added
- Feature flag to either keep using country flags or show locale names in language switcher

### Fixed
- Fix deprecation for symfony/config 4.2+

### Changed
- Replace country flags with locale names in language switcher

### Removed
- support for php 5 and php 7.0

## [2.4.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.3.1...2.4.0) - 2018-12-03

### Added
- Added global locale switcher

### Fixed
- Global search on translatable fields

## [2.3.1](https://github.com/sonata-project/SonataTranslationBundle/compare/2.3.0...2.3.1) - 2018-06-21
### Fixed
- Added check if translatable checker service exists
- language switcher does not have the same style in modal

## [2.3.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.2.0...2.3.0) - 2018-02-02
### Added
- Added Russian translations
- Compatibility with Symfony4

### Changed
- Changed internal folder structure to `src`, `tests` and `docs`
- Switch all templates references to Twig namespaced syntax
- Switch from templating service to sonata.templating
- Removed usage of old form type aliases

### Fixed
- Fix RuntimeException in sonata:admin:explain command

### Removed
- support for old versions of php and Symfony

## [2.2.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.1.1...2.2.0) - 2017-09-26
### Added
- Support of Twig 2.0
- Added phpcr lists translations

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
