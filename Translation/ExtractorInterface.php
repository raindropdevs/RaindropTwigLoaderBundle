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

/**
 * Extracts translation messages from a database to the catalogue.
 * New found messages are injected to the catalogue using the prefix.
 */
interface ExtractorInterface
{
    /**
     * Extracts translation messages from a database to the catalogue.
     *
     * @param MessageCatalogue $catalogue The catalogue
     */
    public function extract(MessageCatalogue $catalogue);

    /**
     * Sets the prefix that should be used for new found messages.
     *
     * @param string $prefix The prefix
     */
    public function setPrefix($prefix);
}
