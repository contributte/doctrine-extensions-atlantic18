# Nettrine Gedmo Extensions

Wrapper for [Doctrine2 behavioral extensions, Translatable, Sluggable, Tree-NestedSet, Timestampable, Loggable, Sortable](https://github.com/Atlantic18/DoctrineExtensions) to Nette Framework.

## Content

- [Setup](#setup)
- [Configuration](#configuration)
    - [Loggable, Translatable, Treeable](#loggable-translatable-treeable)
    - [Translatable](#translatable)

## Setup

First of all, install and configure [Nettrine DBAL](https://github.com/nettrine/dbal) and [Nettrine ORM](https://github.com/nettrine/orm) packages`.

Install package

```bash
composer require nettrine/extensions
```

Register extension

```yaml
extensions:
    nettrine.extensions: Nettrine\Extensions\DI\DoctrineExtensionsExtension 
```

## Configuration

Configure listeners. By default all listeners are disabled, enable only required listeners.

```yaml
nettrine.extensions: 
    loggable: off
    sluggable: off
    softDeleteable: off
    treeable: off
    blameable: off
    timestampable: off
    translatable: off
    uploadable: off
    sortable: off
    ipTraceable: off
```

### Loggable, Translatable, Treeable

Setup extra entity mapping.

```yaml
extensions:
    orm.annotations: Nettrine\ORM\DI\OrmAnnotationsExtension

orm.annotations:
    paths:
        # your app entities
        - App/Model/Database/Entity
        # doctrine extensions entities
        - Gedmo\Loggable\Entity
        - Gedmo\Loggable\Entity
        - Gedmo\Tree\Entity
```

If you using `nettrine/dbal` all listeners are registered automatically, or you have to register it manually: 

```php
// Get EventManager, from DI or Entity Manager
$evm = $em->getEventManager();

// Register desired listener to event
$evm->addEventSubscriber($listener); 

```
### [Translatable](https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/translatable.md)

TranslatableListener has a complex configuration:

```yaml
nettrine.extensions:
    translatable:
        translatable: cs_CZ
        default: cs_CZ
        translationFallback: off
        persistDefaultTranslation: off
        skipOnLoad: off
```
