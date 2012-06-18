<?php
namespace Cognosys;
use Cognosys\Exceptions\UserError,
	Cognosys\Exceptions\ApplicationError,
	Cognosys\Templates\Decorator,
	Cognosys\Templates\View,
	Cognosys\Validators\Validator,
	Doctrine\DBAL\DriverManager,
	Doctrine\ORM\Tools\Setup,
	Doctrine\ORM\EntityManager,
	App\Cogs\User\Models\Entities\User,
	\ReflectionAnnotatedMethod;

/**
 * This class must be inherited by all controllers,
 * it statically calls their action and view
 * @abstract
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
abstract class Controller extends EntityManager
{
	/**
	 * @var Cognosys\Request
	 */
	private $_request;
	
	/**
	 * @var Cognosys\Response
	 */
	private $_response;

	/**
	 * @var Cognosys\Templates\Decorator
	 */
	private $_decorator;

	/**
	 * @var Cognosys\Templates\View
	 */
	private $_view;
	
	/**
	 * Values from posts
	 * @var array
	 */
	protected $params;
	
	/**
	 * @var Cognosys\Session
	 */
	protected $_session;
	
	/**
	 * @var App\Cogs\User\Models\Entities\User
	 */
	protected $_user;
	
	/**
	 * Do not use this constructor, use the factory method instead
	 * @final
	 * @param array $database_params in order to construct an EntityManager
	 */
	final protected function __construct(array $database_params)
	{
		$connection = DriverManager::getConnection($database_params);
		
		// constructing an EntityManager
		parent::__construct(
			$connection,
			Setup::createAnnotationMetadataConfiguration(
				array(COGS),
				Config::get('development', false)
			),
			$connection->getEventManager()
		);
		
		if (isset($database_params['logging']) && $database_params['logging']) {
			$logger = new \Doctrine\DBAL\Logging\EchoSQLLogger();
			$this->getConnection()->getConfiguration()->setSQLLogger($logger);
		}
	}
	
	/**
	 * Creates a controller instance
	 * @static
	 * @final
	 * @param Cognosys\Request $request
	 * @param Cognosys\Response $response
	 * @param array $database_params
	 * @param Cognosys\Templates\Decorator
	 * @return Cognosys\Controller
	 * @throws Exceptions\UserError if the controller name is unknown
	 */
	static final public function factory(Request $request, Response $response,
										 array $database_params)
	{
		$cog = $response->cog();
		$controller = $response->controller();
		$action = $response->action();
		$params = $response->params();
		$session = Session::instance();
		
		if ($cog === null) {
			throw new UserError(
				"There is no such area: <em>{$response->originalController()}</em>"
			);
		}
		
		// use the namespace inside the application
		$controller_class = "App\\Cogs\\{$cog}\\Controllers\\{$controller}";
		
		// renders the view even if there is no action
		$instance = new $controller_class($database_params);
		$instance->_request = $request;
		$instance->_response = $response;
		$instance->_session = $session;
		$instance->_view = new View($request, $response);
		$instance->_user = $instance->repo(User::classname())->find(
			$session->get('user', false)
		);
		$instance->params = $instance->_getPost();
		
		//LOW: require all models to use in instanceof?
		
		return $instance;
	}
	
	/**
	 * Runs this controller action, printing its view
	 * @final
	 * @return void
	 */
	final public function run()
	{
		$action = $this->_response->action() . 'Action';
		$params = $this->_response->params();

		// get variables of the abstract controller
		$vars = get_object_vars($this);
		
		if (is_callable(array($this, $action))) {
			//TODO: put these assertions in a private method
			$refl = new ReflectionAnnotatedMethod($this, $action);
			
			if ( ! $this->_authorize($refl->getAllAnnotations())) {
				throw new UserError(
					'You do not have permission to access this action'
				);
			}
			
			if (count($params) < $refl->getNumberOfParameters()) {
				throw new UserError('The URL misses some parameters');
			}
			
			call_user_func_array(array($this, $action), $params);
			$this->flush();
			// closes the database connection because only a controller can access it
			$this->close();
		}

		// get only variables set in the controller instance
		$vars = array_diff_key(get_object_vars($this), $vars);
		$this->_view->setVariables($vars);

		if ($this->_request->json() || $this->_request->ajax()) {
			$this->disableDecorator();
		}
		if ( ! is_null($this->_decorator)) {
			$this->_view->setDecorator($this->_decorator);
		}

		$this->_view->render();
		$this->_view->show();

		//FIXME: in the view, we need to know which constraints are in use, in order to hide some information like links to add an item
	}
	
	/**
	 * Persists the entity,
	 * if $flushes is true flushes the entity manager right away
	 * @final
	 * @param Cognosys\Model $entity
	 * @param bool $flushes
	 * @return void
	 */
	final public function persist($entity, $flushes = false)
	{
		parent::persist($entity);
		if ($flushes) {
			$this->flush();
		}
	}
	
	/**
	 * Removes the entity and flushes right away
	 * @final
	 * @param Cognosys\Model $entity
	 * @param bool $flushes
	 */
	final public function remove($entity, $flushes = false)
	{
		parent::remove($entity);
		if ($flushes) {
			$this->flush();
		}
	}
	
	/**
	 * Returns whether the request is AJAX or not
	 * @final
	 * @return bool
	 */
	final public function isAjax()
	{
		return $this->_request->ajax();
	}
	
	/**
	 * Returns whether the request asks for a JSON response
	 * @final
	 * @return bool
	 */
	final public function isJson()
	{
		return $this->_request->json();
	}
	
	/**
	 * Return whether the request is GET
	 * @final
	 * @return bool
	 */
	final public function isGet()
	{
		return $this->_request->method() === 'GET';
	}
	
	/**
	 * Return whether the request is POST
	 * @final
	 * @return bool
	 */
	final public function isPost()
	{
		return $this->_request->method() === 'POST';
	}
	
	/**
	 * Return whether the request is PUT
	 * @final
	 * @return bool
	 */
	final public function isPut()
	{
		return $this->_request->method() === 'PUT';
	}
	
	/**
	 * Return whether the request is DELETE
	 * @final
	 * @return bool
	 */
	final public function isDelete()
	{
		return $this->_request->method() === 'DELETE';
	}
	
	/**
	 * Get the user referenced by the session
	 * @final
	 * @return Cognosys\Model
	 */
	final public function getUser()
	{
		return $this->_user;
	}
	
	/**
	 * Get the request instance
	 * @final
	 * @return Cognosys\Request
	 */
	final public function request()
	{
		return $this->_request;
	}
	
	/**
	 * Get the response instance
	 * @final
	 * @return Cognosys\Response
	 */
	final public function response()
	{
		return $this->_response;
	}

	final public function view()
	{
		return $this->_view;
	}
	
	/**
	 * If none parameter is given, this returns all alerts set,
	 * if just the type is given, this returns the alerts of that type,
	 * else sets an alert in the session, to render in the next request
	 * @final
	 * @param int $type
	 * @param string $message
	 * @return array|void
	 */
	final public function alert($type = null, $message = null)
	{
		if ($message === null) {
			return AlertManager::byType($type);
		}
		
		AlertManager::set($type, $message);
	}
	
	/**
	 * Gets the alerts that are set to a given form field
	 * @final
	 * @param string $field name of the field
	 * @return array
	 */
	final public function alertField($field) {
		$alerts = AlertManager::byField($field);
		return array_filter($alerts, function($alert) use ($field) {
			return $alert->field() === $field;
		});
	}

	final public function setDecorator($filename)
	{
		$this->_decorator = $filename;
	}

	/**
	 * Disables the decorator rendering
	 * @final
	 * @return void
	 */
	final public function disableDecorator()
	{
		$this->_decorator = null;
	}
	
	/**
	 * Test the validity of a model
	 * @final
	 * @param Cognosys\Model $model
	 * @return bool
	 */
	final protected function valid(Model $model)
	{
		$model->validate();
		$errors = Validator::get();
		AlertManager::import($errors);
		
		$metadata = $this->getClassMetadata(get_class($model));
		foreach ($metadata->associationMappings as $assoc) {
			$method = TextUtil::camelize($assoc['fieldName']);
			if (is_numeric($model->$method()) && $model->$method() > 0) {
				// transform the identifier to the entity
				$model->$method($this->getReference(
					$assoc['targetEntity'],
					$model->$method()
				));
				$this->persist($model->$method());
			}
		}
		
		return empty($errors);
	}
	
	/**
	 * Opposite to valid method
	 * @param Cognosys\Model $mode
	 * @return bool
	 * @see valid()
	 */
	final protected function invalid(Model $model)
	{
		return $this->valid($model) === false;
	}
	
	/**
	 * Stops this request sending a header to the browser requesting a redirect
	 * Before redirection the url passes in $this->url($url)
	 * Without parameter, redirect to the index action
	 * If type and message are set, creates an alert message
	 * @final
	 * @param string $url
	 * @param int $type
	 * @param string $message
	 * @return void
	 * @see url()
	 */
	final protected function redirect($url = '', $type = null, $message = null)
	{
		if ( ! is_null($type) && ! is_null($message)) {
			$this->alert($type, $message);
		}

		header('Location: ' . $this->url($url));
		die;
	}
	
	/**
	 * Returns a URL, if the $url given starts with a forward-slash
	 *  it is relative to the host, else it is relative to the controller
	 * @final
	 * @param string $url
	 * @return string
	 */
	final protected function url($url = '')
	{
		if (strpos($url, 'http://') === 0
			|| strpos($url, 'https://') === 0
			|| strpos($url, '//') === 0
		) {
			return $url;
		} elseif ( ! empty($url) && $url[0] === '/') {
			return $this->_request->host() . $url;
		} else {
			return $this->_request->host() . '/'
				. $this->_response->originalController() . '/'
				. $url;
		}
	}
	
	/**
	 * Returns a URL specific to an AJAX request
	 * @param string $url
	 * @return string
	 * @see url()
	 */
	final protected function ajax($url = '')
	{
		if (strpos($url, 'http://') === 0) {
			return $url;
		} elseif ( ! empty($url) && $url[0] === '/') {
			return $this->_request->host() . '/ajax' . $url;
		} else {
			return $this->_request->host() . '/ajax/'
				. $this->_response->originalController() . '/'
				. $url;
		}
	}
	
	/**
	 * A widget factory, returns a widget instance
	 * @return Cognosys\Widget
	 */
	final protected function widget($name)
	{
		//TODO
	}

	/**
	 * Sets the file to be rendered as the view, if the view is equals to
	 * the name of the action there is no need to call this function
	 * @final
	 * @param string $filename name of the view
	 * @return void
	 * @example
	 * $filename = 'edit' if you want to load the file 'edit.php' on
	 * UserController the file must be at App/User/Views/user/edit.php,
	 * if controller is, e.g. NewsItemsController (cog News), view must be at
	 * App/News/Views/news-items/edit.php
	 */
	final protected function render($filename)
	{
		$this->_view->setFile($filename);
	}

	/**
	 * Sets the content to be rendered in the view as the JSON representation
	 * of the array
	 * @final
	 * @param array $values
	 * @return void
	 */
	final protected function renderJson(array $values)
	{
		$this->renderText(json_encode($values));
	}

	/**
	 * Sets the text to be rendered in the view
	 * @final
	 * @param string $text
	 * @return void
	 */
	final protected function renderText($text)
	{
		$this->_view->setText($text);
	}
	
	/**
	 * Given the classname of an entity or the entity itself,
	 * returns its repository, use <ENTITY_NAME>::classname() to get the
	 * complete name of the entity
	 * @final
	 * @param string|Cognosys\Model $class
	 * @return Doctrine\ORM\EntityRepository
	 */
	final protected function repo($class)
	{
		//TODO: throw ApplicationError
		if ($class instanceof Model) {
			$class = get_class($class);
		}
		return $this->getRepository($class);
	}
	
	private function _authorize(array $constraints)
	{
		if (count($constraints) === 0) {
			// for those action which do not need a user logged in
			return true;
		}
		
		if ($this->_user instanceof User === false) {
			$this->_session->set('referer', $this->_request->url());
			$this->redirect('/login');
		}
		
		$authorized = true;
		foreach ($constraints as $constraint) {
			$authorized = $authorized && $constraint->authorize($this);
		}
		
		return $authorized;
	}
	
	/**
	 * Returns the post values and validates CSRF protection
	 * @return array
	 * @throws ApplicationError
	 * @throws UserError
	 */
	private function _getPost()
	{
		$params = array();
		if ($this->isPost() === false) {
			return $params;
		}
		
		$params = $_POST;
		
		// CSRF protection
		if (isset($params['_authtoken_'])) {
			$expected_token = $this->_session->get('_authtoken_');
			if ($expected_token !== $params['_authtoken_']) {
				throw new UserError(
					'You should ask for a form before submitting one'
				);
			}
		} else {
			throw new ApplicationError(
				'Form does not have a "_authtoken_" field'
			);
		}
		
		return $params;
	}
}
