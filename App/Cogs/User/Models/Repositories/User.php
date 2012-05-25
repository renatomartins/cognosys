<?php
namespace App\Cogs\User\Models\Repositories;
use Doctrine\ORM\EntityRepository;

class User extends EntityRepository
{
	public function byLoginAndPassword($login, $password)
	{
		return $this->findOneBy(array(
			'login'		=> $login,
			'password'	=> $password
		));
	}
	
	public function byLogin($login)
	{
		return $this->findOneBy(array(
			'login' => $login
		));
	}
}
