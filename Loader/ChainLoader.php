<?php

namespace Raindrop\TwigLoaderBundle\Loader;

/**
 * This class exists to make TwigLoaderBundle compatible
 * with KnpMenuBundle. https://github.com/KnpLabs/KnpMenuBundle
 */
class ChainLoader extends \Twig_Loader_Chain {
    public function addPath($path) {
        // my code?
        foreach ($this->loaders as $loader) {
            if ($loader instanceof \Twig_Loader_Filesystem) {
                $loader->addPath($path);
            }
        }
    }
}