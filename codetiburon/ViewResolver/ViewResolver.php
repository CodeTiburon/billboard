<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\ViewResolver;

class ViewResolver implements
    ViewResolverInterface,
    LayoutResolverInterface
{
    protected $paths = [];
    protected $layout = null;

    public function addPath($path)
    {
        $this->paths[] = rtrim($path, '/\\');
        return $this;
    }

    public function resolve($path)
    {
        if ($path[0] === '/') {
            return $path;
        }

        foreach ($this->paths as $base) {
            $fullpath = $base . DIRECTORY_SEPARATOR . $path;

            if (file_exists($fullpath)) {
                return $fullpath;
            }
        }

        return false;
    }

    public function setLayout($layoutPath)
    {
        $this->layout = $layoutPath;
        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }
}