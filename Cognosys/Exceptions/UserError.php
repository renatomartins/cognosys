<?php
namespace Cognosys\Exceptions;
use Cognosys\Controller,
	Cognosys\Templates\Decorator,
	Cognosys\Templates\View,
	Cognosys\Error;

class UserError extends Error
{
	public function handle($request, $response, $template)
	{
		$view = new View($request, $response);
		$view->setDecorator($template);
		$view->setText($this->message);
		$view->render();
		$view->show();
	}
}
