<?php
namespace Core;
use \Exception;

/**
 * Classes that handle errors happening in the application
 * @author Renato S. Martins
 * @abstract
 */
//TODO: let the user recover from the error
abstract class Error extends Exception
{
	protected $message;
	
	/**
	 * @param string $message
	 */
	public function __construct($message)
	{
		$this->message = $message;
		parent::__construct($message, E_ERROR);
	}
	
	/**
	 * Handle the error
	 * @abstract
	 * @param Core\Request $request
	 * @param Core\Response $response
	 * @return void
	 */
	abstract public function handle($request, $response);
	
	/**
	 * Returns the content to display to the user
	 * @abstract
	 * @return string
	 */
	abstract protected function view();
}
