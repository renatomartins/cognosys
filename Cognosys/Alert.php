<?php
namespace Cognosys;

/**
 * Just an alert message container
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Alert
{
	const ERROR		= 'error';
	const WARNING	= 'warning';
	const INFO		= 'info';
	const SUCCESS	= 'success';
	
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
webtool.alert('{$this->_message}', '{$this->_type}')
EOT;
	}
}
