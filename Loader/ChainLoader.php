<?php

namespace Raindrop\TwigLoaderBundle\Loader;

use \Twig_Loader_Chain as BaseChainLoader;

/**
 * This class exists to make TwigLoaderBundle compatible
 * with KnpMenuBundle @ https://github.com/KnpLabs/KnpMenuBundle
 * Bridges some methods to FileSystemLoader as most of bundles relies
 * on its existence.
 */
class ChainLoader extends BaseChainLoader {

    public function addPaths($paths) {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    public function addPath($path) {
        if (is_array($path)) {
            $this->addPaths($path);
            return;
        }

        foreach ($this->loaders as $loader) {
            if ($loader instanceof \Twig_Loader_Filesystem) {
                $loader->addPath($path);
            }
        }
    }

    public function setPaths($paths, $namespace) {
        foreach ($this->loaders as $loader) {
            if ($loader instanceof \Twig_Loader_Filesystem) {
                $loader->setPaths($paths, $namespace);
            }
        }
    }

    public function prependPath($path, $namespace) {
        foreach ($this->loaders as $loader) {
            if ($loader instanceof \Twig_Loader_Filesystem) {
                $loader->prependPath($path, $namespace);
            }
        }
    }
}