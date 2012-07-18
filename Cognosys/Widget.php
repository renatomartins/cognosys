<?php
namespace Cognosys;
use Cognosys\Controller,
	Cognosys\Templates\View,
	Cognosys\Exceptions\ApplicationError;

/**
 * A widget loads views without a decorator
 * and can be called multiple times from other controllers and views in order
 * to render parameterized little bits of views
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
abstract class Widget
{
	static protected $controller;

	/**
	 * @var Cognosys\Templates\View
	 */
	private $_view;

	/**
	 * @var array
	 */
	private $_params;

	/**
	 * Implement this method to send values to the widget view
	 * This is called when rendering the widget
	 * @abstract
	 * @return void
	 */
	abstract public function run();

	/**
	 * Set/gets a controller on/from the widget
	 * A controller must be set so the widgets have access to the database, etc.
	 * @static
	 * @param Cognosys\Controller $controller
	 * @return void
	 * @throws Cognosys\Exceptions\ApplicationError if no controller has been set
	 */
	static public function controller($controller = null)
	{
		if (is_null($controller) || ! $controller instanceof Controller) {
			if ( ! isset(self::$controller)) {
				throw new ApplicationError(
					'A controller must be set in the widget class'
				);
			}
			return self::$controller;
		}
		self::$controller = $controller;
	}

	/**
	 * Constructs a widget
	 * @static
	 * @param string $cog_dashed cog name with dashes instead of uppercased chars (ex: 'user')
	 * @param string $widget_dashed widget name with dashes (ex: 'option-panel')
	 * @param array $params parameters to pass to the widget
	 * @return Cognosys\Widget
	 */
	static public function factory($cog_dashed, $widget_dashed, $params = array())
	{
		$cogname = TextUtil::classify($cog_dashed);
		$widget_class = TextUtil::classify($widget_dashed);
		$widget_class = "App\\Cogs\\{$cogname}\\Widgets\\{$widget_class}";
		return new $widget_class($cogname, $widget_dashed, $params);
	}

	public function __construct($cogname, $widget_dashed, $params = array())
	{
		$this->_view = View::forWidget($cogname, $widget_dashed);
		$this->_params = $params;
	}

	/**
	 * Runs the widget subclass and renders the view's content
	 * @final
	 * @return string
	 */
	final public function render()
	{
		$this->run();

		$this->_view->setVariables(get_object_vars($this));
		$this->_view->render();
		return $this->_view->content();
	}

	/**
	 * Widget parameters
	 * @final
	 * @return array
	 */
	final public function params()
	{
		return $this->_params;
	}
}
