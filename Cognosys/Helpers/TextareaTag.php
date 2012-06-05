<?php
namespace Cognosys\Helpers;
use Cognosys\AlertManager,
	Cognosys\Exceptions\ApplicationError;

/**
 * Helper to render a textarea tag
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class TextareaTag extends HelperTag
{
	protected $name;
	protected $rows;
	protected $cols;
	protected $text;
	
	protected function validate()
	{
		// intentionally left blank...
	}
	
	public function idToken()
	{
		return 'tta';
	}
	
	public function render($content)
	{
		$result = "<textarea {$this->getAttributes()}>{$content}</textarea>";
		
		$alerts = AlertManager::byField($this->name);
		foreach ($alerts as $alert) {
			$result .= $alert->render();
		}
		
		return $result;
	}
}
