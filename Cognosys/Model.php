<?php
namespace Cognosys;
use Cognosys\Exceptions\ApplicationError,
	Cognosys\Validators\Validator;

/**
 * This class must be inherited by all models (a.k.a. as entities when merged
 * into the Doctrine's EntityManager)
 * @abstract
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
abstract class Model
{
	/**
	 * @var bool
	 */
	private $_valid;
	
	/**
	 * This method includes all necessary validations to the model properties,
	 * using the classes in Cognosys\Validators\
	 * Useful mainly because data from forms must be validated and/or converted
	 * to expected value types.
	 * @example
	 * A checkbox value should be converted to a boolean:
	 * <code>
	 * Boolean::cast($this, 'property_name');
	 * </code>
	 * @abstract
	 * @return void
	 */
	abstract public function validate();
	
	/**
	 * Returns a model instance based on an array of properties.
	 * This properties are only set if they exist in the first place.
	 * This also set errors in the model, if any
	 * @static
	 * @param array $params
	 * @return Cognosys\Model
	 * @example
	 * User::create(array('name' => 'John', 'surname' => 'Doe'))
	 *   => new User instance with name and surname variables set
	 */
	static public function create(array $properties = array())
	{
		$instance = new static;
		foreach ($properties as $name => $value) {
			// use function property_exists and sets the value
			if (property_exists($instance, $name)) {
				$instance->$name = $value;
			}
		}
		
		return $instance;
	}
	
	/**
	 * Helper function to get the class name
	 * @static
	 * @return string
	 * @example User::classname() -> "App\Cogs\User\Models\Entities\User"
	 */
	static public function classname()
	{
		return get_called_class();
	}
	
	/**
	 * Converts this model in an array with the property names as keys
	 * @return array
	 */
	public function toArray()
	{
		//FIXME: remove _valid from the array
		return get_object_vars($this);
	}
	
	public function toJson()
	{
		//TODO: how to transform this model to json? external library?
		return json_encode($this->toArray());
	}
}
