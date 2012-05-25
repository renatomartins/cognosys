<?php
namespace App\Cogs\User\Models\Entities;
use Cognosys\Model,
	\DateTime,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="App\Cogs\User\Models\Repositories\User")
 * @Table(name="users")
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class User extends Model
{
	//TODO: is this safe to share??
	static private $_salt = '5Ga01jfgas0Gashdas9Jf';
	
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string", length=128)
	 */
	protected $name;
	
	/**
	 * @Column(type="string", length=128)
	 */
	protected $email;
	
	/**
	 * @Column(type="string", length=32)
	 */
	protected $phone;
	
	/**
	 * @Column(type="string", length=32)
	 */
	protected $phone2;
	
	/**
	 * @Column(type="string", length=32)
	 */
	protected $login;
	
	/**
	 * @Column(type="string", length=40)
	 */
	protected $password;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $register_date;
	
	/**
	 * @ManyToOne(targetEntity="Group", inversedBy="users")
	 * @JoinColumn(name="group_id")
	 */
	protected $group;
	
	/**
	 * @ManyToMany(targetEntity="Role")
	 * @JoinTable(name="users_roles",
	 * 		joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
	 * 		inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")}
	 * )
	 */
	protected $role_exceptions;
	
	// if a model needs default values in constructor,
	//   set them in a static create like this
	/*static public function create(array $params)
	{
		$instance = parent::create($params);
		// set defaults
		$instance->register_date = new DateTime;
		return $instance;
	}*/
	
	static public function generatePassword($login, $password)
	{
		return sha1(md5($password) . md5($login) . self::$_salt);
	}
	
	public function __construct()
	{
		$this->role_exceptions = new ArrayCollection();
	}
	
	public function id()
	{
		return $this->id;
	}
	
	public function role_exceptions()
	{
		return $this->role_exceptions;
	}
	
	public function name($name = null)
	{
		if (is_null($name)) {
			return $this->name;
		}
		$this->name = $name;
	}
	
	public function email($email = null)
	{
		if (is_null($email)) {
			return $this->email;
		}
		$this->email = $email;
	}
	
	public function phone($phone = null)
	{
		if (is_null($phone)) {
			return $this->phone;
		}
		$this->phone = $phone;
	}
	
	public function phone2($phone = null)
	{
		if (is_null($phone)) {
			return $this->phone2;
		}
		$this->phone2 = $phone;
	}
	
	public function login($login = null)
	{
		if (is_null($login)) {
			return $this->login;
		}
		$this->login = $login;
	}
	
	public function password($password = null)
	{
		if (is_null($password)) {
			return $this->password;
		}
		$this->password = self::generatePassword($this->login, $password);
	}
	
	public function register_date($register_date = null)
	{
		if (is_null($register_date)) {
			return $this->register_date;
		}
		$this->register_date = $register_date;
	}
	
	public function group($group = null)
	{
		if (is_null($group)) {
			return $this->group;
		}
		$this->group = $group;
	}
	
	public function hasRole($role)
	{
		$role_names = array_map(function($r) {
			return $r->name();
		}, $this->role_exceptions->toArray());
		
		// if user has role SUPER, let him do anything
		if (in_array('SUPER', $role_names)) {
			return true;
		}
		
		return in_array($role, $role_names) || $this->group->hasRole($role);
	}
	
	public function hasGroup($group, $bubble = true)
	{
		$result = $this->group->name() === $group;
		if ($bubble) {
			$result = $result || $this->group->hasParent($group);
		}
		return $result;
	}
	
	public function validate()
	{
		//TODO: implement method 'validate'
	}
}
