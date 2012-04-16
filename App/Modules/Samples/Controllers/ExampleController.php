<?php
namespace App\Modules\Samples\Controllers;
use Core\Controller,
	App\Modules\User\Models\Entities\User,
	App\Modules\User\Models\Entities\UserGroup,
	App\Modules\User\Models\Units\Privileges,
	\DateTime;

/**
 * @deprecated
 */
class ExampleController extends Controller
{
	public function indexAction()
	{
		/*$manager = $this->repo(User::name())->byLogin('johndoe');
		
		$group = new UserGroup('A-Team', 'A Team', $manager,
			Privileges::READ_STUFF | Privileges::WRITE_STUFF,
			new DateTime, UserGroup::STATE_INACTIVE
		);
		$this->persist($group, true);*/
		
		/*$this->user = new User(7217245, 'John X', 'john.x.ext@nsn.com',
			'', '', 'johnx', 'hNFASV9SD5hdhg54UGDF45YAT578HG45572ASI946SD45A70954',
			new DateTime, $group, Privileges::READ_STUFF
		);
		
		$this->persist($this->user, true);*/
		
		//$this->user = $this->repo(User::name())->byLogin('johnx');
		
	}
	
	public function refererAction()
	{
		
	}
	
	public function getPostAction()
	{
		var_dump($this->params);
	}
	
	public function groupAction($id)
	{
		$this->group = $this->repo(UserGroup::classname())->find($id);
		$this->user = $this->group->manager;
	}
	
	/**
	 * @example
	 */
	public function johnDoeRegistrationAction()
	{
		$this->user = $this->repo(User::classname())->findOneBy(array(
			'login' => 'johndoe'
		));
		$this->user->register_date = new DateTime;
	}
	
	/**
	 * @example
	 */
	public function changePrivilegesAction($user_id, $privileges)
	{
		$this->user = $this->repo(User::classname())->find($user_id);
		$this->user->privileges = $privileges;
		
		$this->render('index');
	}
	
	/**
	 * @example
	 */
	public function loginAction()
	{
		$this->hello = 'Hello!';
		
		// by default renders login.php
	}
}
