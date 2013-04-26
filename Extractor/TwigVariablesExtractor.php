<?php

namespace Raindrop\TwigLoaderBundle\Extractor;

/**
 * Description of VariablesExtractor
 *
 * @author teito
 */
class TwigVariablesExtractor {

    protected $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function extract($templateName) {
        $twigTemplate = $this->twig->getLoader()->getSource($templateName);

        $visitor = new VariableNodeVisitor;
        $this->twig->addNodeVisitor($visitor);
        $visitor->enable();
        $this->twig->parse($this->twig->tokenize($twigTemplate));
        $variables = array_keys($visitor->getMessages());
        $visitor->disable();

        return array_filter($variables, function ($variable) {
            return $variable != '_key';
        });
    }
}

?>
