<?php
namespace App\Cogs\Samples\Controllers;
use Cognosys\Controller,
	App\Cogs\User\Models\Entities\User;

/**
 * @deprecated possibly
 */
class SessionController extends Controller
{
	public function indexAction()
	{
		
	}
	
	public function getAction()
	{
		$this->redirect(); // or in config file: "/session/get": "session"
	}
	
	public function setAction($key, $value)
	{
		$this->_session->set($key, $value);
		
		$this->render('index');
	}
}
