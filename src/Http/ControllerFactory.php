<?php
namespace PastryBag\Http;

use Cake\Core\App;
use Cake\Http\ControllerFactory as DefaultControllerFactory;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Utility\Inflector;
use PastryBag\Di\PastryBag;

class ControllerFactory extends DefaultControllerFactory
{
    /**
     * {@inheritdoc}
     *
     * @param \Cake\Network\Request $request The request to build a controller for.
     * @param \Cake\Network\Response $response The response to use.
     * @return \Cake\Controller\Controller
     */
    public function create(Request $request, Response $response)
    {
        $pluginPath = $controller = null;
        $namespace = 'Controller';
        if (isset($request->params['plugin'])) {
            $pluginPath = $request->params['plugin'] . '.';
        }
        if (isset($request->params['controller'])) {
            $controller = $request->params['controller'];
        }
        if (isset($request->params['prefix'])) {
            if (strpos($request->params['prefix'], '/') === false) {
                $namespace .= '/' . Inflector::camelize($request->params['prefix']);
            } else {
                $prefixes = array_map(
                    'Cake\Utility\Inflector::camelize',
                    explode('/', $request->params['prefix'])
                );
                $namespace .= '/' . implode('/', $prefixes);
            }
        }
        $firstChar = substr($controller, 0, 1);

        // Disallow plugin short forms, / and \\ from
        // controller names as they allow direct references to
        // be created.
        if (strpos($controller, '\\') !== false ||
            strpos($controller, '/') !== false ||
            strpos($controller, '.') !== false ||
            $firstChar === strtolower($firstChar)
        ) {
            return $this->missingController($request);
        }

        $className = App::classname($pluginPath . $controller, $namespace, 'Controller');
        if (!$className) {
            return $this->missingController($request);
        }

        $di = PastryBag::container();
        if (isset(class_parents($className)['PastryBag\Controller\Controller'])) {
            $instance = $di->newInstance($className);
            $instance->construct($request, $response, $controller);
        } else {
            $di->params[$className] = [$request, $response, $controller];
            $instance = $di->newInstance($className);
        }

        return $instance;
    }
}
