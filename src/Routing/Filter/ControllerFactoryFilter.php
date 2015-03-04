<?php
namespace PipingBag\Routing\Filter;

use Cake\Core\App;
use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;
use Cake\Utility\Inflector;
use PipingBag\Module\DefaultModule;
use PipingBag\Module\HttpModule;
use Ray\Di\InjectorInterface;
use Ray\Di\Di\Inject;

/**
 * A dispatcher filter that builds the controller to dispatch
 * in the request.
 *
 * This filter resolves the request parameters into a controller
 * instance and attaches it to the event object.
 */
class ControllerFactoryFilter extends DispatcherFilter {

/**
 * Priority is set high to allow other filters to be called first.
 *
 * @var int
 */
	protected $_priority = 49;

/**
 * The injector instance
 *
 * @var Ray\Di\InjectorInterface
 */
	protected $_injector;

/**
 * Constructor.
 *
 * @param Ray\Di\InjectorInterface $injector the Injector instance
 * @param PipingBag\Module\DefaultModule $module The bindings container
 */
	public function __construct(InjectorInterface $injector, $config = []) {
		parent::__construct($config);
		$this->_injector = $injector;
    }

/**
 * @Inject
 */
    public function setModule(DefaultModule $module) {
        $this->_module = $module;
    }

/**
 * Resolve the request parameters into a controller and attach the controller
 * to the event object.
 *
 * @param \Cake\Event\Event $event The event instance.
 * @return void
 */
	public function beforeDispatch(Event $event) {
		$request = $event->data['request'];
		$response = $event->data['response'];

		$event->data['controller'] = $this->_getController($request, $response);
	}

/**
 * Get controller to use, either plugin controller or application controller
 *
 * @param \Cake\Network\Request $request Request object
 * @param \Cake\Network\Response $response Response for the controller.
 * @return mixed name of controller if not loaded, or object if loaded
 */
	protected function _getController($request, $response) {
		$pluginPath = $controller = null;
		$namespace = 'Controller';
		if (!empty($request->params['plugin'])) {
			$pluginPath = $request->params['plugin'] . '.';
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

		$this->_module->add(new HttpModule($className, $request, $response));
		return $this->_injector->getInstance($className);
	}

}
