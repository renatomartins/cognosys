<?php
namespace Core\Helpers;
use \ReflectionObject,
	\ReflectionProperty;

/**
 * Parent class of all HTML tags
 * @author Renato S. Martins
 */
abstract class HelperTag
{
	/**
	 * @var Core\Controller
	 */
	private $_controller;
	/**
	 * @var array
	 */
	private $_params;
	/**
	 * @var mixed
	 */
	private $_content;
	
	protected $class;
	protected $id;
	protected $onclick;
	protected $ondblclick;
	protected $onkeydown;
	protected $onkeypress;
	protected $onkeyup;
	protected $onmousedown;
	protected $onmousemove;
	protected $onmouseout;
	protected $onmouseover;
	protected $onmouseup;
	protected $style;
	protected $title;
	
	/**
	 * Initializes a helper with access to the controller, and if needed, some
	 * parameters and content,
	 * validates its properties and returns its HTML representation
	 * @static
	 * @param Core\Controller $controller
	 * @param array $params
	 * @param mixed $content Tipically, a string, an array or a Closure
	 * @return string
	 */
	static public function create($controller, array $params = array(), $content = null)
	{
		$helper = new static($controller, $params, $content);
		$helper->validate();
		return $helper->render($content);
	}
	
	private function __construct($controller, $params, $content)
	{
		$this->_controller = $controller;
		$this->_params = $params;
		$this->_content = $content;
		
		foreach ($params as $name => $value) {
			if (property_exists($this, $name)) {
				$this->$name = $value;
			}
		}
		
		if (isset($this->id) === false) {
			$this->id = substr(md5(microtime()), 0, 5) . '-' . $this->idToken();
		}
	}
	
	/**
	 * Convert the protected properties of the helper tag a string of attribute
	 * keys and values
	 * @return string
	 * @example
	 * "name='title' class='big-input' value='Insert a title' "
	 */
	protected function getAttributes()
	{
		$attributes = '';
		
		$reflex = new ReflectionObject($this);
		$reflex_props = $reflex->getProperties(ReflectionProperty::IS_PROTECTED);
		foreach ($reflex_props as $property) {
			$name = $property->name;
			if (isset($this->$name)) {
				$attributes .= "{$name}='{$this->$name}' ";
			}
		}
		return $attributes;
	}
	
	/**
	 * @return Core\Controller
	 */
	protected function controller()
	{
		return $this->_controller;
	}
	
	/**
	 * @return array
	 */
	protected function params()
	{
		return $this->_params;
	}
	
	/**
	 * @return mixed
	 */
	protected function content()
	{
		return $this->_content;
	}
	
	/**
	 * Checks if the properties are valid
	 * @abstract
	 * @return void
	 * @throws Core\Exception\ApplicationError
	 */
	abstract protected function validate();
	
	/**
	 * Returns a unique id to this html element
	 * @abstract
	 * @return string
	 * @example
	 * For a SelectTag returns something like 'slt'
	 */
	abstract public function idToken();
	
	/**
	 * Returns the tag html representation
	 * @abstract
	 * @param mixed $content
	 * @return string
	 */
	abstract public function render($content);
}
