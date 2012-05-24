<?php
namespace Cognosys\Constraints;
use Cognosys\Controller,
	Cognosys\Constraint,
	Cognosys\ConstraintArgumentException;

/**
 * Constraint to check if the current user is authorized
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class User extends Constraint
{
	/**
	 * @var string
	 */
	public $login;
	
	public function authorize(Controller $controller)
	{
		if (isset($this->login) === false) {
			throw new ConstraintArgumentException(get_class($this), 'login');
		}
		
		$user = $controller->getUser();
		return $user->login() === $this->login;
	}
}
