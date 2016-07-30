<?php
namespace PastryBag\Routing\Filter;

use Cake\Core\App;
use Cake\Routing\Filter\ControllerFactoryFilter as DefaultControllerFactory;
use Cake\Utility\Inflector;
use PastryBag\Di\PastryBag;

class ControllerFactoryFilter extends DefaultControllerFactory
{

    /**
     * {@inheritdoc}
     *
     * @param \Cake\Network\Request $request
     * @param \Cake\Network\Response $response
     * @return bool|object
     */
    protected function _getController($request, $response)
    {
        $pluginPath = $controller = null;
        $namespace = 'Controller';
        if (!empty($request->params['plugin'])) {
            $pluginPath = $request->params['plugin'] . '.';
        }
        if (!empty($request->params['controller'])) {
            $controller = $request->params['controller'];
        }
        if (!empty($request->params['prefix'])) {
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
        if (strpos($controller, '\\') !== false ||
            strpos($controller, '.') !== false ||
            $firstChar === strtolower($firstChar)
        ) {
            return false;
        }
        $className = false;
        if ($pluginPath . $controller) {
            $className = App::classname($pluginPath . $controller, $namespace, 'Controller');
        }
        if (!$className) {
            return false;
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
