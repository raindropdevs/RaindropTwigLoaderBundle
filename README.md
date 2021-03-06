# Raindrop Twig Loader Bundle

[![Build Status](https://travis-ci.org/raindropdevs/RaindropTwigLoaderBundle.png?branch=master)](https://travis-ci.org/raindropdevs/RaindropTwigLoaderBundle)

This bundle adds database support for twig templates. It substitutes Twig_Loader_FileSystem with Twig_Loader_Chain and appends Database and FileSystem loader.
To load from database, use database:<name> syntax into render method.


### **INSTALLATION**:

First add the dependency to your composer.json` file:

    "require": {
        ...
        "raindrop/twigloader-bundle": "dev-master"
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

There is also a `TwigTemplateAdmin` class activated only if `SonataAdmin` bundle is enabled in your AppKernel.php.

### **CONFIGURATION**:

This the default configuration and can be overridden in `app/config/config.yml`:

``` yaml
doctrine:
  dbal:
    types:
      json: Sonata\Doctrine\Types\JsonType
```

and

``` yaml
raindrop_twig_loader:
    chain:
        replace_twig_loader: true
        loaders_by_id:
            raindrop_twig.loader.filesystem: 10
            raindrop_twig.loader.database: 20
```

This configures twig chain loader and append those specified above.

### **USAGE**:

#### Load template from database:

``` php
class myController {
	public function indexAction()
    {
		return $this->render('database:contact_us_en');
	}
}
```

The database loader will load the template using <entity>::getTemplate() method and pass result to controller render method.
