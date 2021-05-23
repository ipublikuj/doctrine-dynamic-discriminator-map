# Dynamic discriminator map

[![Build Status](https://badgen.net/github/checks/ipublikuj/doctrine-dynamic-discriminator-map/master?cache=300&style=flast-square)](https://github.com/ipublikuj/doctrine-dynamic-discriminator-map)
[![Code coverage](https://badgen.net/coveralls/c/github/ipublikuj/doctrine-dynamic-discriminator-map?cache=300&style=flast-square)](https://coveralls.io/github/ipublikuj/doctrine-dynamic-discriminator-map)
![PHP](https://badgen.net/packagist/php/ipub/doctrine-dynamic-discriminator-map?cache=300&style=flast-square)
[![Licence](https://badgen.net/packagist/license/ipub/doctrine-dynamic-discriminator-map?cache=300&style=flast-square)](https://github.com/ipublikuj/doctrine-dynamic-discriminator-map/blob/master/LICENSE.md)
[![Downloads total](https://badgen.net/packagist/dt/ipub/doctrine-dynamic-discriminator-map?cache=300&style=flast-square)](https://packagist.org/packages/ipub/doctrine-dynamic-discriminator-map)
[![Latest stable](https://badgen.net/packagist/v/ipub/doctrine-dynamic-discriminator-map/latest?cache=300&style=flast-square)](https://packagist.org/packages/ipub/doctrine-dynamic-discriminator-map)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

This extension adds support for dynamic definition of [Doctrine](http://www.doctrine-project.org/) discriminator map, so you can easily extend your entities in modules etc.

## Installation

The best way to install **ipub/doctrine-dynamic-discriminator-map** is using  [Composer](http://getcomposer.org/):

```sh
composer require ipub/doctrine-dynamic-discriminator-map
```

After that you have to register extension in config.neon.

```neon
extensions:
    dynamicDiscriminatorMap: IPub\DoctrineDynamicDiscriminatorMap\DI\DoctrineDynamicDiscriminatorMapExtension
```

## Documentation

Learn how to use Doctrine dynamic discriminator map in [documentation](https://github.com/iPublikuj/doctrine-dynamic-discriminator-map/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/doctrine-dynamic-discriminator-map](http://github.com/iPublikuj/doctrine-dynamic-discriminator-map).
