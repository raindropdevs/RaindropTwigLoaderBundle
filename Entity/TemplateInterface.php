<?php

namespace Raindrop\TwigLoaderBundle\Entity;

interface TemplateInterface
{
    public function getName();
    public function getType();
    public function getTemplate();
    public function getUpdated();
}
