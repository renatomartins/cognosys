<?php
namespace Cognosys\Constraints;
use Cognosys\Constraint,
	Cognosys\Controller;

class Post extends Constraint
{
	public function authorize(Controller $controller)
	{
		return $controller->isPost();
	}
}
