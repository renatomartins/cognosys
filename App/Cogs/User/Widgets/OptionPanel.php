<?php
namespace App\Cogs\User\Widgets;
use Cognosys\Widget,
	App\Cogs\User\Models\Entities\User;

class OptionPanel extends Widget
{
	public function run()
	{
		$user = self::$controller->user();
		if ($user instanceof User) {
			$this->username = $user->name();
		}
	}
}
