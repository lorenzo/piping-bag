<?php
declare(strict_types=1);

namespace PipingBag\Routing\Filter;

use Cake\Core\App;
use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;
use Cake\Utility\Inflector;
use Cake\Routing\Filter\ControllerFactoryFilter as ParentFactory;
use PipingBag\Module\HttpModule;
use PipingBag\Di\PipingBag;
use Cake\Http\ServerRequest;
use Cake\Http\Response;
use Cake\Http\ControllerFactory;
use Cake\Controller\Controller;

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
     * @param ServerRequest $request Request object
     * @param Response $response Response for the controller.
     *
     * @return mixed name of controller if not loaded, or object if loaded
     */
    protected function _getController($request, $response)
    {
        $pluginPath = $controller = null;

        if ($request->getParam('plugin')) {
            $pluginPath = $request->getParam('plugin') . '.';
        }

        if ($pluginPath) {
            return parent::_getController($request, $response);
        }

        if ($request->getParam('controller')) {
            $controller = $request->getParam('controller');
        }

        $factory = new ControllerFactory();
        $className = $factory->getControllerClass($request);
        $instance = PipingBag::get($className);

        if (method_exists($instance, 'viewBuilder')) {
            $instance->viewBuilder();
        } else {
            $instance->viewPath = null;
        }

        $instance->name = $controller;
        $instance->setRequest($request);
        $instance->response = $response;
        return $instance;
    }
}
