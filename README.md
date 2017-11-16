# Doctrine Extensions

Wrapper for [Doctrine2 behavioral extensions, Translatable, Sluggable, Tree-NestedSet, Timestampable, Loggable, Sortable](https://github.com/Atlantic18/DoctrineExtensions) to Nette Framework.

> Adapt to Nette Framework (**2.3**, **2.4**)

-----

[![Build Status](https://img.shields.io/travis/nettrine/extensions.svg?style=flat-square)](https://travis-ci.org/nettrine/extensions)
[![Code coverage](https://img.shields.io/coveralls/nettrine/extensions.svg?style=flat-square)](https://coveralls.io/r/nettrine/extensions)
[![Licence](https://img.shields.io/packagist/l/nettrine/extensions.svg?style=flat-square)](https://packagist.org/packages/nettrine/extensions)

[![Downloads this Month](https://img.shields.io/packagist/dm/nettrine/extensions.svg?style=flat-square)](https://packagist.org/packages/nettrine/extensions)
[![Downloads total](https://img.shields.io/packagist/dt/nettrine/extensions.svg?style=flat-square)](https://packagist.org/packages/nettrine/extensions)
[![Latest stable](https://img.shields.io/packagist/v/nettrine/extensions.svg?style=flat-square)](https://packagist.org/packages/nettrine/extensions)
[![Latest unstable](https://img.shields.io/packagist/vpre/nettrine/extensions.svg?style=flat-square)](https://packagist.org/packages/nettrine/extensions)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/nettrine/nettrine.svg?style=flat-square)](http://bit.ly/nettrine)

## Install

```sh
composer require nettrine/extensions
```

## Dependencies

| Package                   | Version          |
|---------------------------|------------------|
| nette/di                  | ~2.3.0 \| ~2.4.0 |
| gedmo/doctrine-extensions | ~2.4.0           |

## Inspired

Heavily inspired by these plugins, thank you guys.

- https://github.com/stof/StofDoctrineExtensionsBundle
- https://github.com/rixxi/gedmo

## Usage

By default, all behavioral extensions are enabled.

```yaml
extensions:
    gedmo: Nettrine\Extensions\DI\DoctrineExtensionsExtension

gedmo: 
    translatable:
        translatable: cs_CZ
        default: cs_CZ
        translationFallback: off
        persistDefaultTranslation: off
        skipOnLoad: off

    annotations:
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

## Maintainers

<table>
  <tbody>
    <tr>
      <td align="center">
        <a href="https://github.com/f3l1x">
            <img width="150" height="150" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=150">
        </a>
        </br>
        <a href="https://github.com/f3l1x">Milan Felix Šulc</a>
      </td>
      <td align="center">
        <a href="https://github.com/benijo">
            <img width="150" height="150" src="https://avatars3.githubusercontent.com/u/6731626?v=3&s=150">
        </a>
        </br>
        <a href="https://github.com/benijo">Josef Benjač</a>
      </td>
    </tr>
  <tbody>
</table>

---

Thank you for testing, reporting and contributing.
