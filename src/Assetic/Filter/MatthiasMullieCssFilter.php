<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2014 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assetic\Filter;

use Assetic\Asset\AssetInterface;
use MatthiasMullie\Minify;

/**
 * Filters assets through Phpwee.
 *
 * @link https://github.com/searchturbine/phpwee-php-minifier
 * @author Dany Henriquez <dany.henriquez@outlook.com>
 */


class MatthiasMullieCssFilter implements FilterInterface
{
    private $filters;
    private $plugins;

    public function __construct()
    {
        $this->filters = array();
        $this->plugins = array();
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }

    public function setFilter($name, $value)
    {
        $this->filters[$name] = $value;
    }

    public function setPlugins(array $plugins)
    {
        $this->plugins = $plugins;
    }

    public function setPlugin($name, $value)
    {
        $this->plugins[$name] = $value;
    }

    public function filterLoad(AssetInterface $asset)
    {
    }

    public function filterDump(AssetInterface $asset)
    {
        $filters = $this->filters;
        $plugins = $this->plugins;

        if (isset($filters['ImportImports']) && true === $filters['ImportImports']) {
            if ($dir = $asset->getSourceDirectory()) {
                $filters['ImportImports'] = array('BasePath' => $dir);
            } else {
                unset($filters['ImportImports']);
            }
        }

        $minifier = new Minify\CSS();
        $minifier->add($asset->getContent());
        
        $asset->setContent($minifier->minify(), $filters, $plugins);
    }
}
