<?php
namespace Cognosys\Templates;
use Cognosys\Template;

class Decorator extends Template
{
	private $_name;

	public function __construct($request, $response, $filename)
	{
		$this->_request = $request;
		$this->_response = $response;
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
