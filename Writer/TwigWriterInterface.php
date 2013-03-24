<?php

namespace Raindrop\TwigLoaderBundle\Writer;

/**
 * TwigWriterInterface is the interface implemented by the twig writers
 */
interface TwigWriterInterface 
{
    /**
     * Writes the twig fetched from database
     * 
     * @param array $twigs   Twig templates
     * @param array $options Options that are used by the writer
     */    
    public function writeTwigs(array $twigs, $options = array());
}
