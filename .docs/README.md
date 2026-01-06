# Contributte Doctrine Extensions Atlantic18

Integration of [Gedmo/DoctrineExtensions](https://github.com/doctrine-extensions/DoctrineExtensions) (Atlantic18) for Nette Framework.

## Content

- [Installation](#installation)
- [Configuration](#configuration)
  - [Listeners](#listeners)
  - [Translatable](#translatable)
  - [IpTraceable](#iptraceable)
- [Entity mapping](#entity-mapping)
- [Examples](#examples)

## Installation

Install package using composer.

```bash
composer require nettrine/extensions-atlantic18
```

Register prepared [compiler extension](https://doc.nette.org/en/dependency-injection/nette-container) in your `config.neon` file.

```neon
extensions:
    nettrine.extensions.atlantic18: Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension
```

> [!NOTE]
> This extension requires [nettrine/dbal](https://github.com/contributte/doctrine-dbal) and [nettrine/orm](https://github.com/contributte/doctrine-orm) to be installed and configured.

## Configuration

### Listeners

By default, all listeners are disabled. Enable only the ones you need.

```neon
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

Here is the list of all available options with their types.

```neon
nettrine.extensions.atlantic18:
    loggable: <bool>
    sluggable: <bool>
    softDeleteable: <bool>
    treeable: <bool>
    blameable: <bool>
    timestampable: <bool>
    translatable: <bool|structure>
    uploadable: <bool>
    sortable: <bool>
    ipTraceable: <bool|structure>
```

For example, enable timestampable and sluggable:

```neon
nettrine.extensions.atlantic18:
    timestampable: true
    sluggable: true
```

> [!TIP]
> Take a look at more information in official Gedmo documentation:
> - https://github.com/doctrine-extensions/DoctrineExtensions/tree/main/doc

### Translatable

TranslatableListener has a complex configuration:

```neon
nettrine.extensions.atlantic18:
    translatable:
        translatable: cs_CZ
        default: en_US
        translationFallback: false
        persistDefaultTranslation: false
        skipOnLoad: false
```

| Option | Type | Description |
|--------|------|-------------|
| `translatable` | `string` | Current locale for translations |
| `default` | `string` | Default locale |
| `translationFallback` | `bool` | Use fallback locale if translation not found |
| `persistDefaultTranslation` | `bool` | Persist default locale translation |
| `skipOnLoad` | `bool` | Skip translations on entity load |

> [!TIP]
> Take a look at more information in official Gedmo documentation:
> - https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/translatable.md

### IpTraceable

IpTraceable requires client IP address:

```neon
nettrine.extensions.atlantic18:
    ipTraceable:
        ipValue: @Nette\Http\IRequest::getRemoteAddress()
```

Or provide a static IP:

```neon
nettrine.extensions.atlantic18:
    ipTraceable:
        ipValue: '127.0.0.1'
```

> [!TIP]
> Take a look at more information in official Gedmo documentation:
> - https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/ip_traceable.md

## Entity mapping

Gedmo 3.x uses **PHP 8 attributes** for entity mapping. No additional configuration is required.

```php
<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class Article
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(type: 'string', unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private string $slug;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'update')]
    private \DateTime $updatedAt;

}
```

For **Loggable**, **Translatable**, and **Tree** behaviors, you need to set up extra entity mapping:

```neon
nettrine.orm:
    entityManagerDecoratorClass: Nettrine\ORM\EntityManagerDecorator
    configuration:
        driver: pdo_pgsql
        ...

    dql:
        ...

services:
    # Register Gedmo entity paths
    nettrine.orm.xmlDriver:
        setup:
            - addPaths([%vendorDir%/gedmo/doctrine-extensions/src/Translatable/Entity])
            - addPaths([%vendorDir%/gedmo/doctrine-extensions/src/Loggable/Entity])
            - addPaths([%vendorDir%/gedmo/doctrine-extensions/src/Tree/Entity])
```

## Examples

> [!TIP]
> Take a look at more examples in [contributte/doctrine](https://github.com/contributte/doctrine/tree/master/.docs).
