<?php
namespace Cognosys;
use \Helper,
	Cognosys\Exceptions\ApplicationError;

abstract class Template
{
	protected $_path;
	protected $_filename;
	protected $_content;
	protected $_variables;
	protected $_controller;

	public function setFile($filename)
	{
		$this->_filename = $filename . '.php';
	}

	public function setVariables($vars)
	{
		$this->_variables = $vars;
	}

	public function render()
	{
		$file = $this->_path . $this->_filename;
		if ( ! is_readable($file)) {
			throw new ApplicationError(
				'Create a file for this view or send some text to the template'
			);
		}

		// ugly ugly ugly!!!
		require_once COGNOSYS . 'Helper.php';
		Helper::$controller = $this->_controller;

		if (isset($this->_variables)) {
			extract($this->_variables);
		}
		ob_start();
		include $file;
		$this->_content = ob_get_clean();
	}

	public function content()
	{
		return $this->_content;
	}

	protected function setPath($path)
	{
		$this->_path = $path;
	}
}
