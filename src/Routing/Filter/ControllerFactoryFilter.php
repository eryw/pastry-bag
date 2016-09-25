<?php
namespace PastryBag\Routing\Filter;

use PastryBag\Http\ControllerFactory;
use Cake\Routing\Filter\ControllerFactoryFilter as CakeControllerFactoryFilter;

/**
 * A dispatcher filter that builds the controller to dispatch
 * in the request.
 *
 * This filter resolves the request parameters into a controller
 * instance and attaches it to the event object.
 */
class ControllerFactoryFilter extends CakeControllerFactoryFilter
{
    /**
     * Gets controller to use, either plugin or application controller.
     *
     * @param \Cake\Network\Request $request Request object
     * @param \Cake\Network\Response $response Response for the controller.
     * @return \Cake\Controller\Controller
     */
    protected function _getController($request, $response)
    {
        $factory = new ControllerFactory();

        return $factory->create($request, $response);
    }
}
