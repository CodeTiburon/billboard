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

    /**
     * @var array Request input
     */
    protected $input;

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

    /**
     * Render view
     *
     * @param $__path
     * @param array $__vars
     * @throws ViewNotFoundException
     */
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
        $CONTENT = $HEADER = $FOOTER = '';

        ob_start();
        require($__viewPath);
        $CONTENT = ob_get_clean();

        if ($__layoutPath) {
            ob_start();
            require($__layoutPath);
            $CONTENT = ob_get_clean();
        }

        echo $CONTENT;
    }

    /**
     * Output JSON
     * @param array $vars
     */
    public function json($vars)
    {
        header('Content-Type: application/json');
        echo json_encode($vars);
    }

    /**
     * @param null $name
     * @param bool $default
     * @return array|bool|mixed
     */
    public function getInput($name = null, $default = false)
    {
        if ($this->input === null) {
            $contentType = $_SERVER["CONTENT_TYPE"];

            if ($contentType === 'application/x-www-form-urlencoded' ||
                $contentType === 'multipart/form-data'
            ) {
                $this->input = $_POST;
            } elseif (preg_match('/\b(json|javascript)\b/i', $contentType)) {
                $data = file_get_contents('php://input');
                $this->input = json_decode($data, true);
            } else {
                $this->input = [];
            }
        }

        if ($name) {
            return isset($this->input[$name]) ? $this->input[$name] : $default;
        } else {
            return $this->input;
        }
    }

    /**
     * @param null $name
     * @param bool $default
     * @return bool
     */
    public function getQuery($name = null, $default = false)
    {
        if ($name) {
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        } else {
            return $_GET;
        }
    }
}