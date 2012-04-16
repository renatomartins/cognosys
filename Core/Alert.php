<?php
namespace Core;

/**
 * Just an alert message container
 * @author Renato S. Martins
 */
class Alert
{
	const ERROR		= 1;
	const WARNING	= 2;
	const INFO		= 3;
	const SUCCESS	= 4;
	
	/**
	 * One of the constants of the AlertManager
	 * @var int
	 */
	private $_type;
	
	/**
	 * @var string
	 */
	private $_message;
	
	/**
	 * Set in case this is an error message for a form field
	 * @var string
	 */
	private $_field;
	
	public function __construct($type, $message, $field = null)
	{
		$this->_type = $type;
		$this->_message = $message;
		$this->_field = $field;
	}
	
	public function type()
	{
		return $this->_type;
	}
	
	public function message()
	{
		return $this->_message;
	}
	
	public function field()
	{
		return $this->_field;
	}
	
	public function render()
	{
		return <<<EOT
<div class="alert-{$this->_type}">{$this->_message}</div>
EOT;
	}
}
