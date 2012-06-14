<?php
namespace Cognosys\Helpers;
use Cognosys\AlertManager,
	Cognosys\Exceptions\ApplicationError;

/**
 * Helper to render an input element tag
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class InputTag extends HelperTag
{
	protected $type;
	protected $value;
	protected $name;
	protected $placeholder;
	protected $checked;
	
	protected function validate()
	{
		if (isset($this->checked) && ! (bool)$this->checked) {
			unset($this->checked);
		}
		
		if ( ! isset($this->type)) {
			throw new ApplicationError('Please specify a type to the input tag');
		}
	}
	
	public function idToken()
	{
		switch ($this->type) {
			case 'text':		return 'txt';
			case 'password':	return 'pwd';
			case 'checkbox':	return 'ckb';
			case 'radio':		return 'rad';
			case 'submit':		return 'sbt';
			case 'reset':		return 'rst';
			case 'file':		return 'fil';
			case 'hidden':		return 'hdn';
			case 'button':		return 'btn';
		}
		return $this->type;
	}
	
	public function render($content)
	{
		$result = "<input {$this->getAttributes()}>";
		
		$alerts = AlertManager::byField($this->name);
		foreach ($alerts as $i => $alert) {
			//$result .= $alert->render();
			$result .= "<span class='help-inline'>{$alert->message()}</span>";
		}
		
		return $result;
	}
}
