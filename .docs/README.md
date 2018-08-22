# Nettrine / Extensions

## Content

- [Minimal configuration](#configuration)
- Listeners
    - [Translatable](#translatable)

## Minimal configuration

At first, you will needed Doctrine ORM/DBAL extension. Take a look at [Nettrine/ORM](https://github.com/nettrine/orm)
and [Nettrine/DBAL](https://github.com/nettrine/dbal) in this organization. 

Secondly, place `DoctrineExtensionsExtension` in your NEON config file.

```yaml
extensions:
    nettrine.extensions: Nettrine\Extensions\DI\DoctrineExtensionsExtension 
```

And setup listeners. By default all listeners are disabled, enable only required listeners.

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

For **loggable**, **translatable** and **treeable** you gonna needed to setup extra entity mapping.

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
## Listeners

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
