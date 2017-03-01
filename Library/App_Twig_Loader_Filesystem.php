<?php

namespace APP\Library;

class App_Twig_Loader_Filesystem extends \Twig_Loader_Filesystem
{

    protected function findTemplate($name)
    {
        if (isset($this->cache[$name]))
        {
            return $this->cache[$name];
        }
        if (is_file($name))
        {
            $this->cache[$name] = $name;
            return $name;
        }
        return parent::findTemplate($name);
    }

}
