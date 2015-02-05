# Disable git ignore management

By default the installer will add any modules you install to a `.gitignore` file in the `magento-root-dir`.
When un-installing a module it will also remove those entries. It creates an entry for each file created during the 
installation process. This feature is great for avoiding having lots of un-tracked files in your project, however, 
if you do want to disable it, you can do the following:

```json
{
    ...
    "extra":{
        "mci" : {
            "disable-gitignore-manage": true
        }
    }
    ...
}
```