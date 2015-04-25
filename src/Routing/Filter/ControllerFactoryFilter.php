<?php
namespace PipingBag\Routing\Filter;

use Cake\Core\App;
use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;
use Cake\Utility\Inflector;
use Cake\Routing\Filter\ControllerFactoryFilter as ParentFactory;
use PipingBag\Module\HttpModule;
use PipingBag\Di\PipingBag;

/**
 * A dispatcher filter that builds the controller to dispatch
 * in the request.
 *
 * This filter resolves the request parameters into a controller
 * instance and attaches it to the event object.
 */
class ControllerFactoryFilter extends ParentFactory
{

    /**
     * Priority is set high to allow other filters to be called first.
     *
     * @var int
     */
    protected $priority = 50;

    /**
     * Get controller to use, either plugin controller or application controller
     *
     * @param \Cake\Network\Request $request Request object
     * @param \Cake\Network\Response $response Response for the controller.
     * @return mixed name of controller if not loaded, or object if loaded
     */
    protected function _getController($request, $response)
    {
        $pluginPath = $controller = null;
        $namespace = 'Controller';
        if (!empty($request->params['plugin'])) {
            $pluginPath = $request->params['plugin'] . '.';
        }

        if ($pluginPath) {
            return parent::_getController($request, $response);
        }

        if (!empty($request->params['controller'])) {
            $controller = $request->params['controller'];
        }
        if (!empty($request->params['prefix'])) {
            $namespace .= '/' . Inflector::camelize($request->params['prefix']);
        }
        $className = false;
        if ($pluginPath . $controller) {
            $className = App::classname($pluginPath . $controller, $namespace, 'Controller');
        }

        if (!$className) {
            return false;
        }

        $controller = PipingBag::get($className);
        $controller->setRequest($request);
        $controller->response = $response;
        return $controller;
    }
}
