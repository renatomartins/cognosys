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
	protected $_request;
	protected $_response;
	private $_css_files;
	private $_css_snippets;
	private $_js_files;
	private $_js_snippets;

	public function setFile($filename)
	{
		$this->_filename = $filename . '.php';
	}

	public function setVariables($vars)
	{
		$this->_variables = $vars;
	}

	public function getVariables()
	{
		return $this->_variables;
	}

	public function getCssFiles()
	{
		return isset($this->_css_files) ? $this->_css_files : array();
	}

	public function getCssSnippets()
	{
		return isset($this->_css_snippets) ? $this->_css_snippets : array();
	}

	public function getJsFiles()
	{
		return isset($this->_js_files) ? $this->_js_files : array();
	}

	public function getJsSnippets()
	{
		return isset($this->_js_snippets) ? $this->_js_snippets : array();
	}

	public function addCssFile($filename)
	{
		$this->_css_files[] = $filename;
	}

	public function addCssSnippet($snippet)
	{
		$this->_css_snippets[] = $snippet;
	}

	public function addJsFile($filename)
	{
		$this->_js_files[] = $filename;
	}

	public function addJsSnippet($snippet)
	{
		$this->_js_snippets[] = $snippet;
	}

	public function render($view)
	{
		$file = $this->_path . $this->_filename;
		//TODO: should raise a UserError when a URL with an invalid action is requested
		if ( ! is_readable($file)) {
			throw new ApplicationError(
				'Create a file for this view or send some text to the template'
			);
		}

		// ugly ugly ugly!!!
		require_once COGNOSYS . 'Helper.php';
		Helper::addTemplate($view);

		if (isset($this->_variables)) {
			extract($this->_variables);
		}
		ob_start();
		include $file;
		$this->_content = ob_get_clean();
		
		Helper::removeTemplate();
	}

	public function content()
	{
		return $this->_content;
	}

	public function request()
	{
		return $this->_request;
	}

	public function response()
	{
		return $this->_response;
	}

	protected function setPath($path)
	{
		$this->_path = $path;
	}
}
