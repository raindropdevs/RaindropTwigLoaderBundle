# Raindrop Twig Loader Bundle

This bundle adds database support for twig templates. It substitutes Twig_Loader_FileSystem with Twig_Loader_Chain and appends Database and FileSystem loader.
To load from database, use database:<name> syntax into render method.


### **INSTALLATION**:

First add the dependency to your `composer.json` file:

    "require": {
        ...
        "raindrop/twig-loader-bundle": "dev-master"
    },

Then install the bundle with the command:

    php composer.phar update

Enable the bundle in your application kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Raindrop\TwigLoaderBundle\RaindropTwigLoaderBundle(),
    );
}
```

Now the bundle is enabled.

### **CONFIGURATION**:

Add following lines to `app/config/config.yml`:

``` yaml
raindrop_twig_loader:
    chain:
        loaders_by_id:
            twig.filesystem_loader: 100
            twig.database_loader: 200
```        

This will instruct the configuration to detach symfony router, attach chain router
and append symfony standard router and the dynamic one.

The dynamic router fetches and stores routes from/to database using Doctrine ORM.

### **USAGE**:

#### Load template from database:

``` php
class myController {
	public function indexAction() {
		return $this->render('database:contact_us_en');
	}
}
```

The database loader will load the template using <entity>::getData() method and pass result to controller render method.
