<?php
namespace App\Modules\User\Models\Entities;
use Core\Model,
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\ORM\EntityNotFoundException,
	\DateTime;

/**
 * @Entity(repositoryClass="App\Modules\User\Models\Repositories\Group")
 * @Table(name="groups")
 */
class Group extends Model
{
	const STATE_INACTIVE	= 0;
	const STATE_ACTIVE		= 1;
	
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @OneToOne(targetEntity="Group")
	 * @JoinColumn(name="parent_id")
	 */
	protected $parent;
	
	/**
	 * @Column(type="string", length=128)
	 */
	protected $name;
	
	/**
	 * @Column(type="string")
	 */
	protected $description;
	
	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="manager_id")
	 */
	protected $manager;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $creation_date;
	
	/**
	 * @Column(type="boolean")
	 */
	protected $active;
	
	/**
	 * @OneToMany(targetEntity="User", mappedBy="group")
	 */
	protected $users;
	
	/**
	 * @ManyToMany(targetEntity="Role")
	 * @JoinTable(name="groups_roles",
	 * 		joinColumns={@JoinColumn(name="group_id", referencedColumnName="id")},
	 * 		inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")}
	 * )
	 */
	protected $roles;
	
	public function __construct()
	{
		$this->creation_date = new DateTime();
		$this->users = new ArrayCollection();
		$this->roles = new ArrayCollection();
	}
	
	public function id()
	{
		return $this->id;
	}
	
	public function creation_date()
	{
		return $this->creation_date;
	}
	
	public function users()
	{
		return $this->users;
	}
	
	public function roles()
	{
		return $this->roles;
	}
	
	public function parent($parent = null)
	{
		if (is_null($parent)) {
			return $this->parent;
		}
		$this->parent = $parent;
	}
	
	public function name($name = null)
	{
		if (is_null($name)) {
			return $this->name;
		}
		$this->name = $name;
	}
	
	public function description($description = null)
	{
		if (is_null($description)) {
			return $this->description;
		}
		$this->description = $description;
	}
	
	public function manager($manager = null)
	{
		if (is_null($manager)) {
			return $this->manager;
		}
		$this->manager = $manager;
	}
	
	public function active($active = null)
	{
		if (is_null($active)) {
			return $this->active;
		}
		$this->active = $active;
	}
	
	/**
	 * Check if this group as given role
	 * @param string $role
	 * @return bool
	 * 
	 */
	public function hasRole($role)
	{
		$role_names = array_map(function($r) {
			return $r->name();
		}, $this->roles->toArray());
		
		$has = in_array($role, $role_names);
		
		if ($has === false) {
			// stupid work around to check if parent is instanceof Group...
			try {
				$has = $this->parent->hasRole($role);
			} catch (EntityNotFoundException $e) {
				// nothing
			}
		}
		
		return $has;
	}
	
	/**
	 * Check if this group has given parent
	 * @param string $name
	 * @return bool
	 */
	public function hasParent($name)
	{
		try {
			return
				$this->parent->name() === $name
				|| $this->parent->hasParent($name);
		} catch (EntityNotFoundException $e) {
			return false;
		}
	}
	
	public function validate()
	{
		//TODO: implement method 'validate'
	}
}
