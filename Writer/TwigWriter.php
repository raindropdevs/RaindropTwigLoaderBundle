<?php

namespace Raindrop\TwigLoaderBundle\Writer;

/**
 * TwigWriter writes twig loaded from database
 */
class TwigWriter implements WriterInterface
{
    /**
     * {@inheritDoc}
     */    
    public function writeTwigs(array $twigs, $options = array())
    {
        if (!array_key_exists('path', $options)) {
            throw new \InvalidArgumentException('The file writer need a path options.');
        }
        
        // save a file for each twig
        foreach ($twigs as $twig) {
            // explode name string
            $path = explode(':', $twig->getName());
            // get twig name
            $name = array_pop($path);
            $file = $name.'.'.$this->getExtension();

            $fullpath = $options['path'].'/'.$file;

            if (!empty ($path)) {
                $newPath = implode('/', $path);
                $fullpath = $options['path'].'/'.$newPath.'/'.$file;

                // create directory structure if does not exists
                if (!is_dir($options['path'].'/'.$newPath)) {
                    mkdir($options['path'].'/'.$newPath, 0777, true);
                }
            }

            // backup
            if (file_exists($fullpath)) {
                copy($fullpath, $fullpath.'~');
            }

            // save file
            file_put_contents($fullpath, $twig->getTemplate());
        }        
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension()
    {
        return 'twig';
    }    
}
