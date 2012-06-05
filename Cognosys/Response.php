<?php
namespace Cognosys;
use Cognosys\Exceptions\ApplicationError;

/**
 * Redirects the execution to a controller according to the request parameters
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Response
{
	/**
	 * @var Cognosys\Request
	 */
	private $_request;
	
	/**
	 * 
	 * @var string
	 */
	private $_cog;
	
	/**
	 * @var string
	 */
	private $_original_controller;
	
	/**
	 * @var string
	 */
	private $_controller;
	
	/**
	 * @var string
	 */
	private $_action;
	
	/**
	 * @var array
	 */
	private $_params;
	
	/**
	 * @param Cognosys\Request $request
	 * @param array $routes
	 * @throws \Exception if $routes does not have 'default' nor 'match'
	 * @example
	 * $routes array must define 'default' and optionally 'match'
	 * 'match' is an array in which the keys are URLs and the values are the controller, action, ..
	 * 'default' is a string with the controller, action, .. if none of the previous routes match
	 * You can use some metadata: ':controller', ':action', ':params'
	 * If $routes['default'] => '/:controller/:action/:params'
	 * and the request's path is /user/edit/group/2
	 * the Response object would be:
	 *   $this->_controller = 'UserController'
	 *   $this->_action = 'edit'
	 *   $this->_params = array('group', '2')
	 */
	public function __construct(Request $request, array $routes, array $cogs)
	{
		if (isset($routes['default']) === false
			|| isset($routes['match']) === false
		) {
			throw new ApplicationError(
				'array $routes must have key "default" or "match"'
			);
		}
		
		$this->_request = $request;
		$this->_routes = $routes;
		
		$this->_selectRoute();
		$this->_renameProperties();
		$this->_selectCog($cogs);
	}
	
	/**
	 * @return string
	 */
	public function cog()
	{
		return $this->_cog;
	}
	
	/**
	 * @return string
	 */
	public function originalController()
	{
		return $this->_original_controller;
	}
	
	/**
	 * @return string
	 */
	public function controller()
	{
		return $this->_controller;
	}
	
	/**
	 * @return string
	 */
	public function action()
	{
		return $this->_action;
	}
	
	/**
	 * @return array
	 */
	public function params()
	{
		return $this->_params;
	}
	
	public function __toString()
	{
		return "Cog: {$this->_cog}\n"
			. "Controller: {$this->_controller}\n"
			. "Action: {$this->_action}\n"
			. 'Parameters: (' . join(', ', $this->_params) . ')';
	}
	
	/**
	 * Selects the proper route according to this response routes
	 * @return void
	 */
	private function _selectRoute()
	{
		$path =  explode('/', trim($this->_request->path(), '/ '));
		$routes = isset($this->_routes['match'])
			? $this->_routes['match']
			: array();
		
		foreach ($routes as $pattern => $route) {
			$pattern_tokens = explode('/', trim($pattern, '/ '));
			if ($this->_matchCustomPattern($path, $pattern_tokens)) {
				$this->_parseCustomRoute($path, $route);
				return;
			}
		}
		
		if (!isset($this->_routes['default'])) {
			return;
		}
		
		$default = explode('/', trim($this->_routes['default'], '/ '));
		$controller_index = array_search(':controller', $default);
		$action_index = array_search(':action', $default);
		$params_index = array_search(':params', $default);
		
		$this->_controller = isset($path[$controller_index])
			? $path[$controller_index] : null;
		$this->_action = isset($path[$action_index])
			? $path[$action_index] : null;
		$this->_params = array_slice($path, $params_index);
	}
	
	/**
	 * @param array $path the request path
	 * @param array $pattern the pattern to test the matching
	 * @return boolean
	 */
	private function _matchCustomPattern($path, $pattern)
	{
		$i = 0;
		while ($i < count($pattern) && $i < count($path)) {
			if ($path[$i] !== $pattern[$i] && !$this->_isKeyword($pattern[$i])) {
				return false;
			}
			$i++;
		}
		
		return true;
	}
	
	/**
	 * Parses a custom route and sets private variables
	 * @param array $path
	 * @param string $route
	 * @return void
	 */
	private function _parseCustomRoute($path, $route)
	{
		$route = explode('#', trim($route));
		$this->_controller = array_shift($route);
		$this->_action = array_shift($route);
		$this->_params = $route;
	}
	
	/**
	 * Checks if a string is a keyword like :controller or :action
	 * @param string $str
	 * @return boolean
	 */
	private function _isKeyword($str)
	{
		return strlen($str) > 2 && $str[0] === ':';
	}
	
	/**
	 * Renames controller and action
	 * @return void
	 */
	private function _renameProperties()
	{
		$this->_original_controller = $this->_controller;
		
		$this->_controller = $this->_controller === null
			? 'IndexController'
			: ucfirst(TextUtil::camelize($this->_controller)) . 'Controller';
		
		$this->_action = $this->_action === null
			? 'index'
			: TextUtil::camelize($this->_action);
	}
	
	/**
	 * Finds to which cog belongs the selected controller
	 * @param array $cogs
	 * @return void
	 * @throws \Exception
	 */
	private function _selectCog($cogs)
	{
		foreach ($cogs as $cog) {
			if (is_file(COGS . $cog . '/Controllers/' . $this->_controller . '.php')) {
				$this->_cog = $cog;
				return;
			}
		}
		
		$this->_cog = null;
	}
}
