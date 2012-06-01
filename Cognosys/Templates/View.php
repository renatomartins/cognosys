<?php
namespace Cognosys\Templates;
use Cognosys\Controller,
	Cognosys\Template,
	Cognosys\TextUtil;

class View extends Template
{
	private $_decorator;
	private $_text;

	public function __construct(Controller $controller)
	{
		$this->_controller = $controller;
		$cog = $this->_controller->response()->cog();
		$controller = $this->_controller->response()->originalController();
		$action = $this->_controller->response()->action();

		$this->setPath(COGS . "{$cog}/Views/{$controller}/");
		$this->setFile(TextUtil::dasherize($action));
	}

	public function getDecorator()
	{
		return $this->_decorator;
	}

	public function setDecorator(Decorator $decorator)
	{
		$this->_decorator = $decorator;
	}

	public function setText($text)
	{
		$this->_text = $text;
	}

	public function render()
	{
		if (isset($this->_text)) {
			$this->_content = $this->_text;
		} else {
			parent::render();
		}
	}

	public function show()
	{
		if ($this->_decorator instanceof Decorator) {
			$this->_decorator->render();
			$this->_decorator->show();
		} else {
			print $this->content();
		}
	}
}
