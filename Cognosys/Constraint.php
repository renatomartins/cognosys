<?php
namespace Cognosys;
use \Annotation,
	Cognosys\Exceptions\ApplicationError;

/**
 * Defines a constraint to use in the actions of the controllers
 * as an annotation (from addendum library)
 * The annotations that need to check if an annotation of the same
 * type as already pass the test, use the static property $_authorized
 * @author Renato S. Martins <smartins.renato@gmail.com>
 * @abstract
 */
abstract class Constraint extends Annotation
{
	/**
	 * Just to know if we already authorized a constraint of the same type
	 * e.g. if we have a two Group constraints it means that we must resolve one OR another
	 * @var bool
	 */
	static protected $authorized;
	
	abstract public function authorize(Controller $controller);
}

class ConstraintArgumentException extends ApplicationError
{
	public function __construct($constraint, $argument)
	{
		parent::__construct("Constraint {$constraint} misses argument '{$argument}'");
	}
}
