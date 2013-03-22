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

use Symfony\Component\Translation\MessageCatalogue;
use Doctrine\ORM\EntityManager;
use Raindrop\TwigLoaderBundle\Entity\TemplateInterface;

/**
 * DatabaseTwigExtractor extracts translation messages from a twig template stored in the database.
 */
class DatabaseTwigExtractor implements ExtractorInterface
{
    /**
     * Default domain for found messages.
     *
     * @var string
     */
    private $defaultDomain = 'messages';

    /**
     * Prefix for found message.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * The twig environment.
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

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

    /**
     * {@inheritDoc}
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    protected function extractTemplate($template, MessageCatalogue $catalogue)
    {
        $visitor = $this->twig->getExtension('translator')->getTranslationNodeVisitor();
        $visitor->enable();

        $this->twig->parse($this->twig->tokenize($template));

        foreach ($visitor->getMessages() as $message) {
            $catalogue->set(trim($message[0]), $this->prefix.trim($message[0]), $message[1] ? $message[1] : $this->defaultDomain);
        }

        $visitor->disable();
    }    
}
