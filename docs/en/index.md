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

/**
 * @ORM\Entity
 */
class Student extends Person
{
    // ...
}
```

```php
<?php
namespace Your\Namespace\Entity;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Teacher extends Person
{
    // ...
}
```

And that is all. Now just insert configuration of your entities into application config.neon:

```neon
dynamicDiscriminatorMap:
    mapping:
        person:
            entity: Your\Namespace\Entity\Person
            map:
                student: Your\Namespace\Entity\Student
                teacher: Your\Namespace\Entity\Teacher
```

Or if you are using some modular system and want to implement maps dynamically, you cen do it with objects and services>

```php
namespace Your\Module\Namespace\DI;

use Nette;
use Nette\DI;

class YourModuleExtension extends DI\CompilerExtension
{
    public function loadConfiguration()
    {
            // Get container builder
            $builder = $this->getContainerBuilder();

            /**
             * Define dynamic discriminator map for identities
             */
    
            $discriminatorMap = $builder->getDefinition('dynamicDiscriminatorMap.map');

            $mapItem = new DoctrineDynamicDiscriminatorMap\MapItem('person', 'Your\Namespace\Entity\Person');
            $mapItem
                ->addMap('student', 'Your\Namespace\Entity\Student')
                ->addMap('teacher', 'Your\Namespace\Entity\Teacher');

            $discriminatorMap->addSetup('addItem', [$mapItem]);
    }
}
```