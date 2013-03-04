<?php

namespace Raindrop\TwigLoaderBundle\Entity;

interface TemplateInterface {
    public function getName();
    public function getData();
    public function getUpdated();
}
