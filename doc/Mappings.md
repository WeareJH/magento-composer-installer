# Mappings

## Mapping in general

A package can provide mappings in several different formats:

1. A mapping in the `composer.json`
3. [modman](https://github.com/colinmollenhour/modman) file
2. MagentoConnect `package.xml file`

As long as one of these mappings can be found, Magento modules are installable.

A repository of composer ready Magento modules can be found on http://packages.firegento.com/

The installer looks for the mapping types in the order specified above. 

## `composer.json` mappings

If you don't like `modman` files, you can define mappings in a package composer.json file instead.

```json
{
   "name": "vendor/module,
   "type": "magento-module",
    "extra": {
        "map": [
            ["themes/default/skin", "public/skin/frontend/foo/default"],
            ["themes/default/design", "public/app/design/frontend/foo/default"],
            ["modules/My_Module/My_Module.xml", "public/app/etc/modules/My_Module.xml"],
            ["modules/My_Module/code", "public/app/code/local/My/Module"],
            ["modules/My_Module/frontend/layout/mymodule.xml", "public/app/design/frontend/base/default/layout/mymodule.xml"]
        ]
    }
}
```