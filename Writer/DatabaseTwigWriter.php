<?php

namespace Raindrop\TwigLoaderBundle\Writer;

use Symfony\Component\Finder\Finder;
use Raindrop\TwigLoaderBundle\Entity\TwigTemplate;

/**
 * DatabaseTwigWriter save twig file on database
 */
class DatabaseTwigWriter implements TwigWriterInterface
{
    /**
     * Set entityManager
     * 
     * @param Doctrine\ORM\EntityManager $entityManager 
     */
    public function setEntityManager($entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Set classEntity
     *
     * @param Raindrop\TwigLoaderBundle\Entity\TemplateInterface $classEntity 
     */
    public function setClassEntity($classEntity) 
    {
        $this->classEntity = $classEntity;
    }

    /**
     * Extracts twig templates from a directory.
     *
     * @param string           $directory The path to look into
     * @param array            $twigs     The array of twig finded in $directory
     */
    public function extract($directory, array $twigs)
    {
        // load any existing twig files
        $finder = new Finder();
        $files = $finder->files()->name('*.twig')->in($directory);

        foreach ($files as $file) {
            array_push($twigs, $file);
        }
    }

    /**
     * {@inheritDoc}
     */    
    public function writeTwigs(array $twigs, $options = array())
    {
        // update database for each twig
        foreach ($twigs as $template) {
            
            $name = str_replace('/', ':', $template->getRelativePathname());
            $name = str_replace('.twig', '', $name);

            // load a existing template files
            $twig = $this->entityManager
                    ->getRepository($this->classEntity)
                    ->findOneByName($name);

            if ($twig) {
                $twig->setTemplate(file_get_contents($template->getPathname()));
            } else {
                $twig = new TwigTemplate();
                $twig->setName($name);
                $twig->setTemplate(file_get_contents($template->getPathname()));
                $twig->setType('database');
            }

            $this->entityManager->persist($twig);            
        } 
        
        $this->entityManager->flush();
    }
}
