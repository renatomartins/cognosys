<?php
namespace Core\Helpers;
use Core\Exceptions\ApplicationError;

/**
 * Helper to render a textarea tag
 * @author Renato S. Martins
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
		
		$alerts = $this->controller()->alertField($this->name);
		foreach ($alerts as $alert) {
			$result .= $alert->render();
		}
		
		return $result;
	}
}
