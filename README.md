# Magento Composer Installer
[![AppVeyor Build Status](https://img.shields.io/appveyor/ci/AydinHassan/magento-composer-installer/master.svg?style=flat-square&label=windows)](https://ci.appveyor.com/project/AydinHassan/magento-composer-installer)
[![Travis Build Status](https://img.shields.io/travis/AydinHassan/magento-module-composer-installer.svg?style=flat-square&label=linux)](https://travis-ci.org/AydinHassan/magento-module-composer-installer) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/WeareJH/magento-composer-installer.svg?style=flat-square)](https://scrutinizer-ci.com/g/WeareJH/magento-composer-installer/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/WeareJH/magento-composer-installer.svg?style=flat-square)](https://scrutinizer-ci.com/g/WeareJH/magento-composer-installer/?branch=master)
[![Documentation Status](https://readthedocs.org/projects/magento-composer-installer/badge/?version=master)](https://readthedocs.org/projects/magento-composer-installer/?badge=master)



> Manage your dependencies with Composer in your Magento project

<a href="http://www.wearejh.com"><img src="http://cl.ly/image/3Y3O0M2z310j/jh-100-red.png" /></a>

The purpose of this project is to 
enable [composer](https://github.com/composer/composer) to install Magento modules,
and automatically integrate them into a Magento installation.

We strongly recommend you to also read the general composer documentations on [getcomposer.org](http://getcomposer.org)

Also you should see [Using composer correctly (confoo) by Igor Wiedler](https://speakerdeck.com/igorw/using-composer-correctly-confoo)

This project is a fork of [Magento Composer Installer](https://github.com/magento-hackathon/magento-composer-installer), so thanks to all the hard work from the following contributors: 

* Daniel Fahlke aka Flyingmana
* Jörg Weller
* Karl Spies
* Tobias Vogt
* David Fuhr
* Amir Tchavoshinia
* Vinai Kopp

## Reason for Fork
The project was forked as we wanted to re-write the majority of the code to aid in debugging, and simplify some of the features
that were added overtime. There also many features that were deemed out-of-scope for the tool. I believe a more concise and stable
project will help adoption of composer in the Magento community.

Also a major aim for the project is to provide sane defaults to allow the installer to work efficiently with as little configuration as 
possible. 

Improvements over the original project (at the time of the fork) include the following:

* Automatic Git Ignore management (Installed modules will be added to the `gitignore` file in the Magento root directory and removed when the module is un-installed)
* When updating modules all old files are removed from the Magento root
* All installed module files and versions are tracked (`vendor/magento-installed.json`), so if you need to re-deploy, just delete that files and run `composer update`

## Project Details
 
This project only covers the custom installer for composer. If you have problems with outdated versions,
need to install magento connect modules or similar, you need to look for 
 
## Documentation

Documentation can be found on [Read the Docs](http://magento-composer-installer.readthedocs.org/en/latest/), the docs are built automatically when pushing to
the master branch of this repository. They are built from the `docs` folder.

## Packages
You can search for Magento module packages on [packages.firegento.com](http://packages.firegento.com/)

You can also use any vcs repository you have access to which has a `composer.json` file in it. See [Composer Docs](https://getcomposer.org/doc/05-repositories.md#vcs) for more information. 

## Quick Setup

We will assume you already have `Composer` installed and are aware of the project. If not, go to https://getcomposer.org/ 
and read the documentation!


### Install a module in your project

Require the installer:

```
$ cd project
$ composer require wearejh/magento-composer-installer
```


If you want to use the public Magento module [repository](http://packages.firegento.com),
add the repository to your `composer.json` like so

```json
{
    ...
    "repositories": [
        {
            "type": "composer",
            "url": "http://packages.firegento.com"
        }
    ],
    ...
}
```

If you want to use a github/git/svn/etc repository that is not available on [Packagist](https://packagist.org/) or [Firegento](http://packages.firegento.com/), add it to your `composer.json` like so

```json
{
    ...
    "repositories": [
        {
            "type": "vcs",
            "url": "the/github/or/git/or/svn/etc/repository/uri-of-the-module"
        }
    ],
    ...
}
```

Require a Magento module in your project and watch it install to the correct location

```
$ cd project
$ composer require "aoepeople/Aoe_Scheduler:0.4.3"
```

You should see the following structure

[![Structure](http://ss.jhf.tw/99TcvMyg4X.png)]


##Contributing

All contributions will require unit-tests. This is in order to keep the project stable and stop regressions.
Don't hesitate to ask for help if you don't know how to write unit tests!

### Running the Unit Tests

```
$ git clone git@github.com:WeareJH/magento-composer-installer.git
$ cd magento-composer-installer
$ composer install
$ vendor/bin/phpunit
```

### Check coding style

This project use PSR2 as the coding style. So if you plan onm contributing please run
the checks before you propose a PR.

```
$ git clone git@github.com:WeareJH/magento-composer-installer.git
$ cd magento-composer-installer
$ composer install
$ vendor/bin/phpcs --standard=PSR2 src
$ vendor/bin/phpcs --standard=PSR2 tests/Jh
```
