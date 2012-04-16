<?php
namespace Core\Helpers;
use Core\Model,
	Core\Exceptions\ApplicationError,
	\Closure;

/**
 * Helper to render a select element tag
 * @author Renato S. Martins
 */
class SelectTag extends HelperTag
{
	protected $name;
	protected $text;
	protected $value;
	
	protected function validate()
	{
		if ($this->content() instanceof Closure === false
			&& is_array($this->content()) === false
		) {
			throw new ApplicationError(
				'Select element needs a Closure or an array as content'
			);
		}
	}
	
	public function idToken()
	{
		return 'slt';
	}
	
	public function render($content)
	{
		if ($content instanceof Closure) {
			ob_start();
			$content($this);
			$options = ob_get_clean();
		} elseif (is_array($content)) {
			$options = '';
			foreach ($content as $option) {
				if (is_array($option)) {
					if (isset($option['text'])) {
						$text = $option['text'];
						$value = isset($option['value'])
							? $option['value']
							: $text;
					} else {
						throw new ApplicationError(
							'Select option must specify in the array at' .
							' least a "text" key and optionally a "value" key'
						);
					}
				} elseif ($option instanceof Model) {
					if (isset($this->text)) {
						$text = $option->{$this->text}();
						$value = isset($this->value)
							? $option->{$this->value}()
							: $option->id();
					} else {
						throw new ApplicationError(
							'Select option must specify which field' .
							' in Model that contains the text'
						);
					}
				} else {
					throw new ApplicationError(
						'Select options must be a Model or an array'
					);
				}
				
				$params = $this->params();
				$selected = isset($params['selected'])
					? $params['selected']
					: false;
				$options .= $this->option($text, $value, $selected);
			}
		}
		
		unset($this->text, $this->value);
		$result = "<select {$this->getAttributes()}>{$options}</select>";
		
		$alerts = $this->controller()->alertField($this->name);
		foreach ($alerts as $alert) {
			$result .= $alert->render();
		}
		
		return $result;
	}
	
	private $_opt_count = 0;
	public function option($text, $value = null, $selected = false)
	{
		++$this->_opt_count;
		return OptionTag::create($this->controller(), array(
			'id'		=> "{$this->id}-opt{$this->_opt_count}",
			'selected'	=> $selected,
			'value'		=> $value
		), $text);
	}
}
