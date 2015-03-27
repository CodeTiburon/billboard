<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\Controller;

use CodeTiburon\Exception\ActionNotFoundException;
use CodeTiburon\Exception\ViewNotFoundException;
use CodeTiburon\ServiceLocator\ServiceLocatorAwareInterface;
use CodeTiburon\ServiceLocator\ServiceLocatorAwareTrait;

abstract class AbstractController implements
    ControllerInterface,
    ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function dispatch($action, $params)
    {
        $action = preg_replace('/[_\-\s]+/', ' ', $action);
        $action = str_replace(' ', '', ucwords($action));
        $action = strtolower($action[0]) . substr($action, 1);

        $method = $action . 'Action';

        if (!method_exists($this, $method)) {
            throw new ActionNotFoundException($action);
        }

        $this->$method(...$params);
    }

    public function render($__path, $__vars = [])
    {
        // Ideally this functionality should be moved to a separate class for example ViewRenderer
        // As an architecture should be complied with "Single responsibility Principle"

        $__viewResolver = $this->serviceLocator->get('ViewResolver');
        $__viewPath = $__viewResolver->resolve($__path);
        $__layoutPath = $__viewResolver->getLayout();

        if (!$__viewPath) {
            throw new ViewNotFoundException($__path);
        }

        extract($__vars);

        ob_start();
        require $__viewPath;
        $CONTENT= ob_get_clean();

        if ($__layoutPath) {
            ob_start();
            require $__layoutPath;
            $CONTENT= ob_get_clean();
        }

        echo $CONTENT;
    }

    public function json($vars)
    {
        echo json_encode($vars);
    }
}