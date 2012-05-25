<?php
namespace App\Cogs\User\Controllers;
use Cognosys\Controller,
	Cognosys\Alert,
	App\Cogs\User\Models\Entities\User;

class UserController extends Controller
{
	public function indexAction()
	{
		$this->redirect('login');
	}
	
	public function loginAction()
	{
		if ($this->isPost()) {
			$password = User::generatePassword(
				$this->params['username'],
				$this->params['password']
			);
			
			$user = $this->repo(User::classname())->byLoginAndPassword(
				$this->params['username'],
				$password
			);
			
			if ($user instanceof User) {
				$this->_session->set('user', $user->id());
				$url = $this->_session->get('referer') ?: '/';
				$this->redirect($url);
			} else {
				$this->alert(Alert::ERROR, 'Error logging in');
			}
		}
	}
	
	public function logoutAction()
	{
		$this->_session->remove('user');
		$this->redirect('/');
	}
}
