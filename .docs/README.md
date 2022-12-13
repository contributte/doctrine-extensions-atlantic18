# Contributte Doctrine Extensions Atlantic18

## Content

Doctrine ([Atlantic18/DoctrineExtensions](https://github.com/Atlantic18/DoctrineExtensions)) extension for Nette Framework

- [Setup](#setup)
- [Configuration](#configuration)
    - [Loggable, Translatable, Treeable](#loggable-translatable-treeable)
    - [Translatable](#translatable)
    - [IpTraceable](#iptraceable)

## Setup

First of all, install and configure [nettrine/dbal](https://github.com/contributte/doctrine-dbal) and [nettrine/orm](https://github.com/contributte/doctrine-orm) packages`.

Install package

```bash
composer require nettrine/extensions-atlantic18
```

Register extension

```yaml
extensions:
    nettrine.extensions.atlantic18: Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension
```

## Configuration

Configure listeners. By default all listeners are disabled, enable only the required ones.

```yaml
nettrine.extensions.atlantic18:
    loggable: false
    sluggable: false
    softDeleteable: false
    treeable: false
    blameable: false
    timestampable: false
    translatable: false
    uploadable: false
    sortable: false
    ipTraceable: false
```

### Loggable, Translatable, Treeable

Setup extra entity mapping.

```yaml
extensions:
    orm.annotations: Nettrine\ORM\DI\OrmAnnotationsExtension

orm.annotations:
    mapping:
        # your app entities
        App\Model\Database\Entity: %appDir%/Model/Database/Entity
        # doctrine extensions entities
        Gedmo\Translatable: %appDir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity
        Gedmo\Loggable: %appDir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity
        Gedmo\Tree: %appDir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity
        ...
```

If you are using `nettrine/dbal` all listeners are registered automatically, otherwise you have to register them manually:

```php
// Get EventManager, from DI or Entity Manager
$evm = $em->getEventManager();

// Register desired listener to event
$evm->addEventSubscriber($listener);

```
### [Translatable](https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/translatable.md)

TranslatableListener has a complex configuration:

```yaml
nettrine.extensions.atlantic18:
    translatable:
        translatable: cs_CZ
        default: cs_CZ
        translationFallback: false
        persistDefaultTranslation: false
        skipOnLoad: false
```

### [IpTraceable](https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/ip_traceable.md)

IpTraceable requires client IP address:

```
nettrine.extensions.atlantic18:
    ipTraceable:
        ipValue: @Nette\Http\IRequest::getRemoteAddress()
```
