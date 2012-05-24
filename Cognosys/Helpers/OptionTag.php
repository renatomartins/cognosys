<?php
namespace Cognosys\Helpers;
use Cognosys\Exceptions\ApplicationError;

/**
 * Helper to render an option tag
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class OptionTag extends HelperTag
{
	protected $value;
	protected $selected;
	
	protected function validate()
	{
		if (isset($this->value) === false) {
			$this->value = $this->content();
		}
		
		if (isset($this->selected) && ! $this->selected) {
			unset($this->selected);
		}
	}
	
	public function idToken()
	{
		return 'opt';
	}
	
	/**
	 * @param string $content
	 */
	public function render($content)
	{
		return "<option {$this->getAttributes()}>{$content}</option>";
	}
}
