<?php
namespace Core\Constraints;
use Core\Constraint;

/**
 * Constraint to check if we are loading the apropriate layout
 * @author Renato S. Martins
 */
//TODO: authorize method
class Layout extends Constraint
{
	/**
	 * @var string
	 */
	public $name;
}
