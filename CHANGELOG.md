# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.1.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.0.2...2.1.0) - 2017-01-17
### Added
- Addded missing flag image `ja.png`

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
