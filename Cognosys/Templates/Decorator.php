<?php
namespace Cognosys\Templates;
use Cognosys\Controller,
	Cognosys\Template;

class Decorator extends Template
{
	private $_name;

	public function __construct(Controller $controller, $filename)
	{
		$this->_controller = $controller;
		$this->_name = $filename;
		$this->setPath(TEMPLATES);
		$this->setFile($filename);
	}

	public function name()
	{
		return $this->_name;
	}

	public function show()
	{
		print $this->content();
	}
}
