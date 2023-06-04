# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [3.3.0](https://github.com/sonata-project/SonataTranslationBundle/compare/3.2.0...3.3.0) - 2023-06-04
### Added
- [[#693](https://github.com/sonata-project/SonataTranslationBundle/pull/693)] Support for SonataBlockBundle 5.0 ([@jordisala1991](https://github.com/jordisala1991))

## [3.2.0](https://github.com/sonata-project/SonataTranslationBundle/compare/3.1.1...3.2.0) - 2023-04-24
### Removed
- [[#682](https://github.com/sonata-project/SonataTranslationBundle/pull/682)] Support for Symfony 4 ([@jordisala1991](https://github.com/jordisala1991))
- [[#682](https://github.com/sonata-project/SonataTranslationBundle/pull/682)] Support for Twig 2 ([@jordisala1991](https://github.com/jordisala1991))
- [[#682](https://github.com/sonata-project/SonataTranslationBundle/pull/682)] Support for `doctrine/persistence` ^2 ([@jordisala1991](https://github.com/jordisala1991))

## [3.1.1](https://github.com/sonata-project/SonataTranslationBundle/compare/3.1.0...3.1.1) - 2023-03-21
### Fixed
- [[#674](https://github.com/sonata-project/SonataTranslationBundle/pull/674)] Always load the TranslatableChecker ([@VincentLanglet](https://github.com/VincentLanglet))

### Removed
- [[#667](https://github.com/sonata-project/SonataTranslationBundle/pull/667)] Support for PHP 7.4 ([@SonataCI](https://github.com/SonataCI))
- [[#667](https://github.com/sonata-project/SonataTranslationBundle/pull/667)] Support for Symfony 6.0 and 6.1 ([@SonataCI](https://github.com/SonataCI))

## [3.1.0](https://github.com/sonata-project/SonataTranslationBundle/compare/3.0.1...3.1.0) - 2022-06-10
### Changed
- [[#636](https://github.com/sonata-project/SonataTranslationBundle/pull/636)] Sonata\TranslationBundle\Checker\TranslationChecker rely on 'is_a' instead of insteance of to fully support string parameter. ([@mpoiriert](https://github.com/mpoiriert))
- [[#637](https://github.com/sonata-project/SonataTranslationBundle/pull/637)] Locale block response is not rendered as private anymore to be compatible with SonataBlockBundle 5 ([@franmomu](https://github.com/franmomu))

### Removed
- [[#640](https://github.com/sonata-project/SonataTranslationBundle/pull/640)] Support of Symfony 5.3 ([@franmomu](https://github.com/franmomu))

## [3.0.1](https://github.com/sonata-project/SonataTranslationBundle/compare/3.0.0...3.0.1) - 2022-03-22
### Fixed
- [[#630](https://github.com/sonata-project/SonataTranslationBundle/pull/630)] Support of new admin declaration with `model_class` as attribute ([@fransweerts](https://github.com/fransweerts))

## [3.0.0](https://github.com/sonata-project/SonataTranslationBundle/compare/3.0.0-rc.1...3.0.0) - 2022-01-27
No changes.

## [3.0.0-rc.1](https://github.com/sonata-project/SonataTranslationBundle/compare/3.0.0-alpha.1...3.0.0-rc.1) - 2021-12-31
### Added
- [[#572](https://github.com/sonata-project/SonataTranslationBundle/pull/572)] Added support for Symfony 6. ([@jordisala1991](https://github.com/jordisala1991))

### Changed
- [[#550](https://github.com/sonata-project/SonataTranslationBundle/pull/550)] `Sonata\TranslationBundle\Admin\Extension\Knplabs\TranslatableAdminExtension` has been marked as `@internal` ([@franmomu](https://github.com/franmomu))
- [[#550](https://github.com/sonata-project/SonataTranslationBundle/pull/550)] `Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension` has been marked as `@internal` ([@franmomu](https://github.com/franmomu))

### Fixed
- [[#556](https://github.com/sonata-project/SonataTranslationBundle/pull/556)] Fixed accessing current locale from `block_locale_switcher.html.twig` template ([@ggabrovski](https://github.com/ggabrovski))

### Removed
- [[#599](https://github.com/sonata-project/SonataTranslationBundle/pull/599)] `UserLocaleSubscriber` class in favor of implementing its own solution ([@franmomu](https://github.com/franmomu))
- [[#558](https://github.com/sonata-project/SonataTranslationBundle/pull/558)] Dropped support for PHP 7.3 ([@franmomu](https://github.com/franmomu))
- [[#559](https://github.com/sonata-project/SonataTranslationBundle/pull/559)] Removed `TranslatableInterface::getLocale()` method ([@franmomu](https://github.com/franmomu))

## [2.10.1](https://github.com/sonata-project/SonataTranslationBundle/compare/2.10.0...2.10.1) - 2021-12-31
### Fixed
- [[#598](https://github.com/sonata-project/SonataTranslationBundle/pull/598)] Creating or updating an entity with translatable sub items ([@franmomu](https://github.com/franmomu))

## [2.10.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.9.1...2.10.0) - 2021-11-17
### Added
- [[#578](https://github.com/sonata-project/SonataTranslationBundle/pull/578)] Support for 5.x versions in `symfony/browser-kit`, `symfony/config`, `symfony/css-selector`, `symfony/dependency-injection`, `symfony/http-foundation`, `symfony/intl`, `symfony/options-resolver`, `symfony/phpunit-bridge` and `symfony/templating` ([@phansys](https://github.com/phansys))

### Deprecated
- [[#571](https://github.com/sonata-project/SonataTranslationBundle/pull/571)] `Sonata\TranslationBundle\Model\TranslatableInterface` in favor of specific package interfaces (gedmo or knplabs) ([@franmomu](https://github.com/franmomu))
- [[#571](https://github.com/sonata-project/SonataTranslationBundle/pull/571)] `Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface` in favor of `Gedmo\Translatable\Translatable` ([@franmomu](https://github.com/franmomu))

## [2.9.1](https://github.com/sonata-project/SonataTranslationBundle/compare/2.9.0...2.9.1) - 2021-10-19
### Fixed
- [[#557](https://github.com/sonata-project/SonataTranslationBundle/pull/557)] Setting the proper locale to Gedmo translatable listener ([@franmomu](https://github.com/franmomu))

## [2.9.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.8.1...2.9.0) - 2021-09-21
### Added
- [[#543](https://github.com/sonata-project/SonataTranslationBundle/pull/543)] Added `LocaleProviderInterface` and `RequestLocaleProvider` to get the locale based on the parameter from the URL or the default configured one ([@franmomu](https://github.com/franmomu))
- [[#543](https://github.com/sonata-project/SonataTranslationBundle/pull/543)] Added `LocaleProvider` service when `knplabs` is enabled to be able to show the content in the proper language ([@franmomu](https://github.com/franmomu))

### Deprecated
- [[#542](https://github.com/sonata-project/SonataTranslationBundle/pull/542)] Deprecated implementing `Sonata\TranslationBundle\Model\TranslatableInterface` in an Entity implementing `Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface` ([@franmomu](https://github.com/franmomu))
- [[#537](https://github.com/sonata-project/SonataTranslationBundle/pull/537)] `Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable` class ([@franmomu](https://github.com/franmomu))
- [[#537](https://github.com/sonata-project/SonataTranslationBundle/pull/537)] `Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation` class ([@franmomu](https://github.com/franmomu))
- [[#537](https://github.com/sonata-project/SonataTranslationBundle/pull/537)] `Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable` class ([@franmomu](https://github.com/franmomu))
- [[#537](https://github.com/sonata-project/SonataTranslationBundle/pull/537)] `Sonata\TranslationBundle\Model\AbstractTranslatable` class ([@franmomu](https://github.com/franmomu))
- [[#537](https://github.com/sonata-project/SonataTranslationBundle/pull/537)] `Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait` trait ([@franmomu](https://github.com/franmomu))
- [[#537](https://github.com/sonata-project/SonataTranslationBundle/pull/537)] `Sonata\TranslationBundle\Traits\Gedmo\TranslatableTrait` trait ([@franmomu](https://github.com/franmomu))
- [[#537](https://github.com/sonata-project/SonataTranslationBundle/pull/537)] `Sonata\TranslationBundle\Traits\TranslatableTrait` trait ([@franmomu](https://github.com/franmomu))

### Removed
- [[#542](https://github.com/sonata-project/SonataTranslationBundle/pull/542)] Removed support of `knplabs/doctrine-behaviors` < 2.2 ([@franmomu](https://github.com/franmomu))

## [2.8.1](https://github.com/sonata-project/SonataTranslationBundle/compare/2.8.0...2.8.1) - 2021-05-18
### Fixed
- [[#491](https://github.com/sonata-project/SonataTranslationBundle/pull/491)] Fixed registering `sonata_translation.listener.translatable` when no `translatable_listener_service` is defined ([@franmomu](https://github.com/franmomu))

## [2.8.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.7.0...2.8.0) - 2021-03-28
### Added
- [[#453](https://github.com/sonata-project/SonataTranslationBundle/pull/453)] Add support for PHP 8.x ([@Yozhef](https://github.com/Yozhef))
- [[#464](https://github.com/sonata-project/SonataTranslationBundle/pull/464)] Added `sonata_translation.gedmo.translatable_listener_service` option to specify the translatable listener service name ([@franmomu](https://github.com/franmomu))

### Deprecated
- [[#464](https://github.com/sonata-project/SonataTranslationBundle/pull/464)] Deprecated not setting `sonata_translation.gedmo.translatable_listener_service` option when using gedmo ([@franmomu](https://github.com/franmomu))

## [2.7.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.6.0...2.7.0) - 2020-11-25
### Added
- [[#416](https://github.com/sonata-project/SonataTranslationBundle/pull/416)]
  Added support for `knplabs/doctrine-behaviors` 2.
([@franmomu](https://github.com/franmomu))
- [[#405](https://github.com/sonata-project/SonataTranslationBundle/pull/405)]
  Added `SonataTranslationBundle.pt.xliff` to support Portuguese(Portugal)
language. ([@joelpro2](https://github.com/joelpro2))
- [[#375](https://github.com/sonata-project/SonataTranslationBundle/pull/375)]
  Add support for SonataBlockBundle 4.0
([@franmomu](https://github.com/franmomu))

### Changed
- [[#408](https://github.com/sonata-project/SonataTranslationBundle/pull/408)]
  Replaced deprecated `GetResponseEvent` with `RequestEvent`.
([@franmomu](https://github.com/franmomu))
- [[#391](https://github.com/sonata-project/SonataTranslationBundle/pull/391)]
  Mark classes as final ([@franmomu](https://github.com/franmomu))

### Deprecated
- [[#393](https://github.com/sonata-project/SonataTranslationBundle/pull/393)]
  Deprecated `Sonata\TranslationBundle\Test\DoctrineOrmTestCase`.
([@franmomu](https://github.com/franmomu))
- [[#393](https://github.com/sonata-project/SonataTranslationBundle/pull/393)]
  Deprecated constructing `AbstractTranslatableAdminExtension` without the
default translation locale. ([@franmomu](https://github.com/franmomu))
- [[#393](https://github.com/sonata-project/SonataTranslationBundle/pull/393)]
  Deprecated protected `AbstractTranslatableAdminExtension::getContainer()`
method. ([@franmomu](https://github.com/franmomu))
- [[#393](https://github.com/sonata-project/SonataTranslationBundle/pull/393)]
  Deprecated protected
`AbstractTranslatableAdminExtension::getTranslationLocales()` method.
([@franmomu](https://github.com/franmomu))
- [[#393](https://github.com/sonata-project/SonataTranslationBundle/pull/393)]
  Deprecated protected
`AbstractTranslatableAdminExtension::getDefaultTranslationLocale()` method.
([@franmomu](https://github.com/franmomu))
- [[#393](https://github.com/sonata-project/SonataTranslationBundle/pull/393)]
  Deprecated constructing
`Sonata\TranslationBundle\Admin\Extension\Gedmo\TranslatableAdminExtension`
without an instance of `TranslatableListener` and `ManagerRegistry`.
([@franmomu](https://github.com/franmomu))

## [2.6.0](https://github.com/sonata-project/SonataTranslationBundle/compare/2.5.0...2.6.0) - 2020-09-04
### Added
- [[#368](https://github.com/sonata-project/SonataTranslationBundle/pull/368)] Twig 3 compatibility ([@jorrit](https://github.com/jorrit))
- [[#367](https://github.com/sonata-project/SonataTranslationBundle/pull/367)] Dutch translation ([@jorrit](https://github.com/jorrit))
- [[#337](https://github.com/sonata-project/SonataTranslationBundle/pull/337)] Added `sonata_language_name` Twig filter ([@franmomu](https://github.com/franmomu))

### Fixed
- [[#369](https://github.com/sonata-project/SonataTranslationBundle/pull/369)] Fixed starting the session in `UserLocaleSubscriber::onInteractiveLogin()` when there is no previous session ([@phansys](https://github.com/phansys))
- [[#348](https://github.com/sonata-project/SonataTranslationBundle/pull/348)] Deprecation notice about ModelManager::getNormalizedIdentifier() on list pages ([@jorrit](https://github.com/jorrit))
- [[#345](https://github.com/sonata-project/SonataTranslationBundle/pull/345)] Deprecation notice caused by use of spaceless tag in block_locale_switcher.html.twig ([@jorrit](https://github.com/jorrit))
- [[#337](https://github.com/sonata-project/SonataTranslationBundle/pull/337)] Use of undefined filter `language_name` ([@franmomu](https://github.com/franmomu))

### Removed
- [[#349](https://github.com/sonata-project/SonataTranslationBundle/pull/349)] Support for PHP < 7.2 ([@wbloszyk](https://github.com/wbloszyk))
- [[#349](https://github.com/sonata-project/SonataTranslationBundle/pull/349)] Support for Symfony < 4.4 ([@wbloszyk](https://github.com/wbloszyk))
- [[#336](https://github.com/sonata-project/SonataTranslationBundle/pull/336)] Support of Symfony < 4.3 ([@franmomu](https://github.com/franmomu))

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
