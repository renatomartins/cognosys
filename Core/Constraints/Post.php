<?php
namespace Core\Constraints;
use Core\Constraint,
	Core\Controller;

class Post extends Constraint
{
	public function authorize(Controller $controller)
	{
		return $controller->isPost();
	}
}
