# Doctrine Extensions

Wrapper for [Doctrine2 behavioral extensions, Translatable, Sluggable, Tree-NestedSet, Timestampable, Loggable, Sortable](https://github.com/Atlantic18/DoctrineExtensions) to Nette Framework.

> Adapt to Nette Framework (**2.3**, **2.4**)

-----

[![Build Status](https://img.shields.io/travis/minetro/doctrine-extensions?style=flat-square)](https://travis-ci.org/minetro/doctrine-extensions)
[![Code coverage](https://img.shields.io/coveralls/minetro/doctrine-extensions?style=flat-square)](https://coveralls.io/r/minetro/doctrine-extensions)
[![Downloads total](https://img.shields.io/packagist/dt/minetro/doctrine-extensions?style=flat-square)](https://packagist.org/packages/minetro/doctrine-extensions)
[![Latest stable](https://img.shields.io/packagist/v/minetro/doctrine-extensions?style=flat-square)](https://packagist.org/packages/minetro/doctrine-extensions)
[![HHVM Status](https://img.shields.io/hhvm/minetro/doctrine-extensions?style=flat-square)](http://hhvm.h4cc.de/package/minetro/doctrine-extensions)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/minetro/nette.svg?style=flat-square)](https://gitter.im/minetro/nette?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Install

```sh
composer require minetro/doctrine-extensions
```

## Inspired

Heavily inspired by these plugin, thank you guys.

- https://github.com/stof/StofDoctrineExtensionsBundle
- https://github.com/rixxi/gedmo

## Dependencies

| Package                   | Version        |
|---------------------------|----------------|
| nette/di                  | ~2.3.0\|~2.4.0 |
| kdyby/doctrine            | >=3.0.0        |
| gedmo/doctrine-extensions | ~2.4.0         |

## Usage

By default, all behavioral extensions are enabled.

```yaml
extensions:
    gedmo: Minetro\DoctrineExtensions\DI\DoctrineExtensionsExtension

gedmo: 
    translatable:
        translatable: cs_CZ
        default: cs_CZ
        translationFallback: off
        persistDefaultTranslation: off
        skipOnLoad: off

    annotations 
        loggable: on
        sluggable: on
        softDeleteable: on
        treeable: on
        blameable: on
        timestampable: on
        translatable: on
        uploadable: on
        sortable: on
        ipTraceable: on
```
