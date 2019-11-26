# Nettrine Extensions Atlantic18

Doctrine ([Atlantic18/DoctrineExtensions](https://github.com/Atlantic18/DoctrineExtensions)) extension for Nette Framework

## Content

- [Setup](#setup)
- [Configuration](#configuration)
    - [Loggable, Translatable, Treeable](#loggable-translatable-treeable)
    - [Translatable](#translatable)
    - [IpTraceable](#iptraceable)

## Setup

First of all, install and configure [Nettrine DBAL](https://github.com/nettrine/dbal) and [Nettrine ORM](https://github.com/nettrine/orm) packages`.

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

Configure listeners. By default all listeners are disabled, enable only required listeners.

```yaml
nettrine.extensions.atlantic18:
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
nettrine.extensions.atlantic18:
    translatable:
        translatable: cs_CZ
        default: cs_CZ
        translationFallback: off
        persistDefaultTranslation: off
        skipOnLoad: off
```

### [IpTraceable](https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/ip_traceable.md)

IpTraceable requires client IP address:

```
nettrine.extensions.atlantic18:
    ipTraceable:
        ipValue: @Nette\Http\IRequest::getRemoteAddress()
```
