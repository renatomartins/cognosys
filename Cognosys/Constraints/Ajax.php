<?php
namespace Cognosys\Constraints;
use Cognosys\Constraint,
	Cognosys\Controller,
	Cognosys\Exceptions\UserError;

/**
 * Constraint to check if this is an AJAX request
 * If so, disables layout
 * @author Renato S. Martins <smartins.renato@gmail.com>
 * @throws Cognosys\Exceptions\UserError if request is not AJAX
 */
class Ajax extends Constraint
{
	public function authorize(Controller $controller)
	{
		if ( ! $controller->isAjax()) {
			throw new UserError(
				'This action is only accessible through AJAX requests'
			);
		}
		
		$controller->disableDecorator();
		return true;
	}
}
