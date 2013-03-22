<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raindrop\TwigLoaderBundle\Translation;

use Symfony\Bridge\Twig\Translation\TwigExtractor;
use Symfony\Component\Translation\Extractor\ExtractorInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Doctrine\ORM\EntityManager;
use Raindrop\TwigLoaderBundle\Entity\TemplateInterface;

/**
 * DatabaseTwigExtractor extracts translation messages from a twig template stored in the database.
 */
class DatabaseTwigExtractor extends TwigExtractor
{
    /**
     * Default domain for found messages.
     *
     * @var string
     */
    private $defaultDomain = 'messages';

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
     * {@inheritDoc}
     */
    public function extract(MessageCatalogue $catalogue)
    {
        // load any existing translation files
        $files = $this->entityManager
                ->getRepository($this->classEntity)
                ->findAll();

        foreach ($files as $file) {
            $this->extractTemplate($file->getTemplate(), $catalogue);
        }
    }
}
