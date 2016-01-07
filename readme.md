# Dynamic discriminator map

[![Build Status](https://img.shields.io/travis/iPublikuj/doctrine-dynamic-discriminator-map.svg?style=flat-square)](https://travis-ci.org/iPublikuj/doctrine-dynamic-discriminator-map)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/doctrine-dynamic-discriminator-map.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/doctrine-dynamic-discriminator-map/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/doctrine-dynamic-discriminator-map.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/doctrine-dynamic-discriminator-map/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/doctrine-dynamic-discriminator-map.svg?style=flat-square)](https://packagist.org/packages/ipub/doctrine-dynamic-discriminator-map)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/doctrine-dynamic-discriminator-map.svg?style=flat-square)](https://packagist.org/packages/ipub/doctrine-dynamic-discriminator-map)
[![License](https://img.shields.io/packagist/l/ipub/doctrine-dynamic-discriminator-map.svg?style=flat-square)](https://packagist.org/packages/ipub/doctrine-dynamic-discriminator-map)
[![Dependency Status](https://img.shields.io/versioneye/d/user/projects/568ecbc2691e2d0038000085.svg?style=flat-square)](https://www.versioneye.com/user/projects/568ecbc2691e2d0038000085)

This extension adds support for dynamic definition of [Doctrine](http://www.doctrine-project.org/) discriminator map, so you can easily extend your entities in modules etc.

## Installation

The best way to install ipub/doctrine-dynamic-discriminator-map is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/doctrine-dynamic-discriminator-map
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
