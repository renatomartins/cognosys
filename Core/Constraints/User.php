<?php
namespace Core\Constraints;
use Core\Controller,
	Core\Constraint,
	Core\ConstraintArgumentException;

/**
 * Constraint to check if the current user is authorized
 * @author Renato S. Martins
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
