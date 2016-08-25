<?php
namespace PastryBag\Controller;

use Cake\Controller\Controller as OriginController;
use Cake\Controller\Exception\MissingActionException;
use Cake\Network\Request;
use Cake\Network\Response;
use PastryBag\Di\PastryBag;


class Controller extends OriginController
{

    /**
     * Override original constructor with do nothing method
     */
    public function __construct()
    {
        // Do nothing
    }

    /**
     * Call the parent (original) constructor.
     *
     * @param Request|null $request
     * @param Response|null $response
     * @param null $name
     * @param null $eventManager
     * @param null $components
     */
    public function construct(Request $request = null, Response $response = null, $name = null, $eventManager = null, $components = null)
    {
        parent::__construct($request, $response, $name, $eventManager, $components);
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed The resulting response.
     * @throws \LogicException When request is not set.
     * @throws \Cake\Controller\Exception\MissingActionException When actions are not defined or inaccessible.
     */
    public function invokeAction()
    {
        $request = $this->request;
        if (!isset($request)) {
            throw new \LogicException('No Request object configured. Cannot invoke action');
        }
        if (!$this->isAction($request->params['action'])) {
            throw new MissingActionException([
                'controller' => $this->name . "Controller",
                'action' => $request->params['action'],
                'prefix' => isset($request->params['prefix']) ? $request->params['prefix'] : '',
                'plugin' => $request->params['plugin'],
            ]);
        }
        $callable = [$this, $request->params['action']];
        $parameters = $this->resolveActionDependency();

        return call_user_func_array($callable, $parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $action
     * @return bool
     */
    public function isAction($action)
    {
        if ($action == 'construct') {
            return false;
        }

        return parent::isAction($action);
    }

    /**
     * Resolve and inject type hinted parameters
     *
     * @return array
     */
    protected function resolveActionDependency()
    {
        $di = PastryBag::container();
        $request = $this->request;
        $reflector = new \ReflectionMethod($this, $request->params['action']);
        $parameters = $request->params['pass'];
        $resolverReflected = null;
        foreach ($reflector->getParameters() as $key => $param) {
            if (!isset($parameters[$key])) {
                $parameters[$key] = null;
            }
            $class = $param->getClass();
            if ($class && !($parameters[$key] instanceof $class->name)) {
                // No way to access registered types in the Aura.Di container, so we must using reflection
                if ($resolverReflected === null) {
                    $resolverReflector = new \ReflectionProperty($di, 'resolver');
                    $resolverReflector->setAccessible(true);
                    $resolverReflected = $resolverReflector->getValue($di);
                }
                if (isset($resolverReflected->types[$class->name])) {
                    $instance = $resolverReflected->types[$class->name]();
                } else {
                    $instance = $di->newInstance($class->name);
                }
                array_splice($parameters, $key, 0, [$instance]);
            }
        }

        return $parameters;
    }
}
