<?php
namespace Cognosys\Templates;
use Cognosys\Response,
	Cognosys\Template,
	Cognosys\TextUtil;

class View extends Template
{
	private $_decorator;
	private $_text;

	public function __construct($request, $response)
	{
		$this->_request = $request;
		$this->_response = $response;

		if ($response instanceof Response) {
			$cog = $response->cog();
			$controller = $response->originalController();
			$action = $response->action();

			$this->setPath(COGS . "{$cog}/Views/{$controller}/");
			$this->setFile(TextUtil::dasherize($action));
		}
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
