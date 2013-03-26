<?php

namespace Raindrop\TwigLoaderBundle\Loader;

class DatabaseTwigLoader implements
    \Twig_LoaderInterface,
    \Twig_ExistsLoaderInterface
{
    const DATABASE_ID = 'database:';

    protected $idLength, $entityManager, $classEntity, $liipTheme, $hit = array();

    public function __construct()
    {
        $this->idLength = strlen(self::DATABASE_ID);
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setClassEntity($classEntity)
    {
        $this->classEntity = $classEntity;
    }

    /**
     * Adds ActiveTheme class form LiipThemeBundle
     *
     * @param Liip\ThemeBundle\ActiveTheme $liipTheme
     */
    public function addLiipTheme($liipTheme)
    {
        $this->liipTheme = $liipTheme;
    }

    /**
     * Returns the liipTheme
     *
     * @return Liip\ThemeBundle\ActiveTheme|null
     */
    public function getLiipTheme()
    {
        return $this->liipTheme;
    }

    /**
     * Returns the active theme
     *
     * @return string|null
     */
    public function getActiveTheme()
    {
        if ($this->getLiipTheme()) {
            return $this->getLiipTheme()->getName();
        } else {
            return null;
        }
    }

    public function getSource($name)
    {
        if ($this->isDatabaseTemplate($name)) {
            $id = $this->getDbName($name);
            if (false === $source = $this->getRecord($id)->getTemplate()) {
                throw new Twig_Error_Loader(
                        sprintf('Template "%s" does not exist.', $name)
                );
            }
        }

        return $source;
    }

    // Twig_ExistsLoaderInterface as of Twig 1.11
    public function exists($name)
    {
        // check if template exists
        if ($this->isDatabaseTemplate($name)) {

            if (isset($this->hit[$name])) {
                return true;
            }
            $id = $this->getDbName($name);
            $record = $this->getRecord($id);

            // cache value while running to avoid further database access
            if ($record) {
                $this->hit[$name] = $record->getUpdated()->getTimestamp();

                return true;
            }
        }

        return null;
    }

    public function getCacheKey($name)
    {
        if ($this->getLiipTheme()) {
            $name.= '|'.$this->getLiipTheme()->getName();
        }

        return $name;
    }

    protected function getLastUpdated($name)
    {
        if (!isset($this->hit[$name])) {
            $id = $this->getDbName($name);
            $this->hit[$name] = $this
                    ->getRecord($id)
                    ->getUpdated()
                    ->getTimestamp();
        }

        return $this->hit[$name];
    }

    public function isFresh($name, $time)
    {
        if ($this->isDatabaseTemplate($name)) {
            return $this->getLastUpdated($name) <= $time;
        }

        return false;
    }

    protected function isDatabaseTemplate($name)
    {
        return substr($name, 0, $this->idLength) === self::DATABASE_ID;
    }

    protected function getDbName($name)
    {
        return substr($name, $this->idLength);
    }

    protected function getRecord($name)
    {
        $theme = $this->getActiveTheme();

        if ($theme) {
            $nameAndTheme = $name . '|' . $theme;

            $template = $this->entityManager
                ->getRepository($this->classEntity)
                ->findOneByName($nameAndTheme);

            if ($template) {
                return $template;
            }
        }

        return $this->entityManager
            ->getRepository($this->classEntity)
            ->findOneByName($name);
    }
}
