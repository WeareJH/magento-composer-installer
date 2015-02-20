# Install Configuration

## Installed Magento Module Repository

When installing your project via `composer update` or `composer install`, the installer will create a state file
which keeps track of all installed Magento modules. This is important due to globs and other features. By default this 
file can be found at `vendor/magento-installed.json`. You can change the location of this folder by setting
the `module-repository-location` config.

```json
{
    ...
    "extra":{
        "mci" : {
            "module-repository-location": "some-other-folder"
        }
    }
    ...
}
```

This file contains the versions of all the Magento modules currently installed and a list of files associated with them.
When you remove a module from your project and run `composer update` the installer will use this repository file to
get a list of files that were installed by the module and it will remove them all. The repository file will then be 
updated to remove that module.

What this means is that if you run `composer update` and no modules have been added, updated or removed. No Magento 
modules will be installed to the `magento-root-dir`. 

### Force Manual Install

If you need to for some reason, force an install of your Magento modules, for example you added some files in the 
vendor directory when developing locally, you can just remove the repository file and run `composer update`.
 
```shell
$ rm vendor/magento-installed.json
$ composer update
```

The installer will assume none of the Magento modules have been installed and it will install them!

## Magento root directory

By default the installer will assume your Magento installation is located in the `htdocs` folder in your project. If 
you want to change this you can use the `magento-root-dir` property.

```json
{
    ...
    "extra":{
        "mci" : {
            "magento-root-dir": "public"
        }
    }
    ...
}
```

## Force install
If a file already exists at the destination when installing, the installer will throw an exception. That is unless
you tell it to force install.

```json
{
    ...
    "extra":{
        "mci" : {
            "force-install": true
        }
    }
    ...
}
```

## Change the install strategy

There are multiple install strategies to choose from. The default is 'symlink'. You can change this by setting the
`install-strategy` property.

The available strategies are:

* symlink
* copy
* link
* none

```json
{
    ...
    "extra":{
        "mci" : {
            "install-strategy": "copy"
        }
    }
    ...
}
```
The `none` strategy essentially disables magento module installation

Please Refer to [modman](https://github.com/colinmollenhour/modman) on how the strategies should work. This tool aims to
follow that.

## Change the install strategy on a per module bases

If you want one or multiple modules to be installed using a different install strategy to the global strategy, you can.
This may be useful for disabling the install of a particular module by setting it's strategy to `none`. Or maybe a
module has some PHP script that references a file using the current location (eg. will fail from a symlink). In this 
case you may want to set the module to deploy via `copy`

```json
{
    ...
	"extra":{
	    "mci": {
	        "install-strategy-overwrites": {
                "aoepeople/aoe_scheduler": "copy",
                "ecomdev/ecomdev_phpunit": "none"
            }
	    }
	}
	...
}
```


## Change install order of modules

In some cases you may want to install your magento modules in specific order.
For example when you have conflicting files and you know which module should be the overwriting one.
In this scenario you would also need to force the install.

 
```json
{
    ...
	"extra":{
	    "mci": {
	    }
        "force-install": true,
        "install-priorities": {
            "aoepeople/aoe_scheduler": "200",
            "ecomdev/ecomdev_phpunit": "400",
        }
	}
	...
}
```

This will install `ecomdev/ecomdev_phpunit` first and `aoepeople/aoe_scheduler` second as `ecomdev/ecomdev_phpunit`
has a higher priority. If there are duplicate mappings over the modules `aoepeople/aoe_scheduler` mappings will take 
precedence. 

The default priority for modules (if you don't specify a customer priority) is `100` and `101` for `copy` install strategy.
This means that modules installed via the `symlink` strategy are installed before the other strategies.


## Ignoring certain files from install
 
There maybe some files you don't want installing from a module. You can do this easily.
You have the option to ignore a certain file from every module, or ignore specific files in specific modules.


```json
{
    ...
	"extra":{
	    "mci" : {
            "install-ignores": {
                "*": [
                    "/index.php"
                ],
                "connect20/mage_core_modules": [
                    "/shell/compiler.php"
                ]
            },
        }
	}
	...
}
```
This example will ignore `/index.php` from every module and `/shell/compiler.php` from the `connect20/mage_core_modules`
module.

## Overwriting a modules map

Maybe a modules map is incorrect or you would just like to map it to a different location. You can do this with the 
following configuration:

```json
{
    ...
	"extra":{
	    "mci" : {
            "map-overwrites": {
                "vendor/module": [
                    ["app/code/community/CompanyDir/ModuleDir/*", "app/code/local/CompanyDir/ModuleDir"]
                ]
            },
        }
	}
	...
}
```

You can overwrite the maps of any modules you want. Just specify the module name, then provide an array of maps.
These will not be merged with the modules maps, they will be used instead. So if you want to change one map, you will 
have to copy them all and just change the one you need.