<?php
namespace Cognosys\Templates;
use Cognosys\Config,
	Cognosys\Response,
	Cognosys\Template,
	Cognosys\TextUtil;

class View extends Template
{
	private $_decorator;
	private $_text;

	static public function forController($request, $response)
	{
		$view = new static;
		$view->_request = $request;
		$view->_response = $response;

		if ($response instanceof Response) {
			$cog = $response->cog();
			$controller = $response->originalController();
			$action = $response->action();

			$view->setPath(COGS . "{$cog}/Views/{$controller}/");
			$view->setFile(TextUtil::dasherize($action));
		}
		return $view;
	}

	static public function forWidget($cogname, $widget_dashed)
	{
		$template = Config::get('templates/default');

		$view = new static;
		$view->setPath(COGS . "{$cogname}/Widgets/{$template}/");
		$view->setFile($widget_dashed);

		return $view;
	}

	public function getDecorator()
	{
		return $this->_decorator;
	}

	public function setDecorator($decorator_file)
	{
		$this->_decorator = new Decorator(
			$this->_request,
			$this->_response,
			$decorator_file
		);
	}

	public function setText($text)
	{
		$this->_text = $text;
	}

	public function render($view = null)
	{
		if (isset($this->_text)) {
			$this->_content = $this->_text;
		} else {
			parent::render($this);
		}
	}

	public function show()
	{
		if ($this->_decorator instanceof Template) {
			$this->_decorator->render($this);
			$this->_decorator->show();
		} else {
			print $this->content();
		}
	}
}
