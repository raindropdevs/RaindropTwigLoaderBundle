<?php

namespace Raindrop\TwigLoaderBundle\Writer;

/**
 * WriterInterface is the interface implemented by the twig writers
 */
interface WriterInterface 
{
    /**
     * Writes the twig fetched from database
     * 
     * @param array $twigs   Twig templates
     * @param array $options Options that are used by the writer
     */    
    public function writeTwigs(array $twigs, $options = array());

    /**
     * Gets the file extension of the writer.
     *
     * @return string file extension
     */
    public function getExtension();    
}
