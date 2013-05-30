<?php

namespace Raindrop\TwigLoaderBundle\Extractor;

/**
 * Find out which variables are being use into a twig template
 *
 * @author teito
 */
class VariableNodeVisitor implements \Twig_NodeVisitorInterface
{

    private $enabled = false;
    private $messages = array();

    public function enable()
    {
        $this->enabled = true;
        $this->messages = array();
    }

    public function disable()
    {
        $this->enabled = false;
        $this->messages = array();
    }

    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        if (!$this->enabled) {
            return $node;
        }

        if (
            $node instanceof \Twig_Node_Expression_Name
) {
            // extract variables nodes
            $this->messages [$node->getAttribute('name')]= true;
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
