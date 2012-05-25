<?php
namespace App\Cogs\User\Models\Repositories;
use Doctrine\ORM\EntityRepository;

class Group extends EntityRepository
{
	public function byName($name)
	{
		return $this->findOneBy(array(
			'name' => $name
		));
	}
}
