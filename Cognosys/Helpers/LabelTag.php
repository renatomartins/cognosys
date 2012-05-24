<?php
namespace Cognosys\Helpers;
use Cognosys\Exceptions\ApplicationError,
	Cognosys\TextUtil,
	\Closure;

/**
 * Helper to render a label tag
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class LabelTag extends HelperTag
{
	protected $for;
	
	protected function validate()
	{
		if (isset($this->for) === false) {
			throw new ApplicationError(
				'Label element requires an attribute "for"'
			);
		}
	}
	
	public function idToken()
	{
		return 'lbl';
	}
	
	/**
	 * @param string|Closure $content
	 */
	public function render($content)
	{
		if ($content === null) {
			$content = TextUtil::humanize($this->for);
		} elseif ($content instanceof Closure) {
			//FIXME: closure should receive a FormTag
			ob_start();
			$content();
			$content = ob_get_clean();
		}
		
		return "<label {$this->getAttributes()}>{$content}</label>";
	}
}
