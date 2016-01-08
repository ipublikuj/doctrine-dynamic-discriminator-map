# Quickstart

This extension adds support for dynamic definition of [Doctrine](http://www.doctrine-project.org/) discriminator map, so you can easily extend your entities in modules etc.

## Installation

The best way to install ipub/doctrine-dynamic-discriminator-map is using [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/doctrine-dynamic-discriminator-map
```

After that you have to register extension in config.neon.

```neon
extensions:
    dynamicDiscriminatorMap: IPub\DoctrineDynamicDiscriminatorMap\DI\DoctrineDynamicDiscriminatorMapExtension
```

## Configuration

At first you have to create your parent entity

```php
<?php
namespace Your\Namespace\Entity;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "person" = "Person"
 * })
 */
class Person
{
    // ...
}
```

Now you can create your children entities like this:

```php
<?php
namespace Your\Namespace\Entity;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

use IPub\DoctrineDynamicDiscriminatorMap\Entities;

/**
 * @ORM\Entity
 */
class Student extends Person implements Entities\IDiscriminatorProvider
{
    // ...

    /**
     * @return string
     */
    public function getDiscriminatorName()
    {
        return 'student';
    }
}
```

```php
<?php
namespace Your\Namespace\Entity;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

use IPub\DoctrineDynamicDiscriminatorMap\Entities;

/**
 * @ORM\Entity
 */
class Teacher extends Person implements Entities\IDiscriminatorProvider
{
    // ...

    /**
     * @return string
     */
    public function getDiscriminatorName()
    {
        return 'teacher';
    }
}
```

Each entity which should be automatically added to discriminator map should implement interface ```IPub\DoctrineDynamicDiscriminatorMap\Entities\IDiscriminatorProvider``` and method ```getDiscriminatorName``` which have to return discriminator name. In case not implementing interface, extension will take short class name, lowercase first letter and use it as key value in discriminator map.


```php
<?php
namespace Your\Namespace\Entity;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

use IPub\DoctrineDynamicDiscriminatorMap\Entities;

/**
 * @ORM\Entity
 */
class Principal extends Person
{
    // ...
    // a key for discriminator map word "principal" will be used
    // ...
}
```

And that is all. No more configuration, everything is now automated.
