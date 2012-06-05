<?php
namespace Cognosys;
use \Exception;

/**
 * Classes that handle errors happening in the application
 * @author Renato S. Martins <smartins.renato@gmail.com>
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
	 * @param Cognosys\Request $request
	 * @param Cognosys\Response $response
	 * @param string $template
	 * @return void
	 */
	abstract public function handle($request, $response, $template);
}
