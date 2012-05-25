<?php
namespace App\Cogs\User\Models\Entities;
use Cognosys\Model;

/**
 * @Entity(repositoryClass="App\Cogs\User\Models\Repositories\Role")
 * @Table(name="roles")
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Role extends Model
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string", length=64)
	 */
	protected $name;
	
	/**
	 * @Column(type="string", length=255)
	 */
	protected $description;
	
	public function id()
	{
		return $this->id;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function description($description = null)
	{
		if (is_null($description)) {
			return $this->description;
		}
		$this->description = $description;
	}
	
	/**
	 * Checks if the the roles of a user meet the required roles of an action
	 * @static
	 * @param array $required name of the roles required by the action
	 * @param array $to_check name of the roles associated with the user
	 * @return bool
	 */
	//TODO: check other constants?
	static public function authorize(array $required, array $to_check)
	{
		$subset = array_intersect($required, $to_check);
		return count($subset) === count($required);
	}
	
	public function validate()
	{
		//TODO: implement method 'validate'
	}
}
