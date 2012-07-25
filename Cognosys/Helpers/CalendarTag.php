<?php
namespace Cognosys\Helpers;
use Cognosys\AlertManager,
	Cognosys\Exceptions\ApplicationError,
	\DateTime;

/**
 * Helper to render an input date with a calendar selector
 * Depends on bootstrap-datepicker
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
//TODO: separate this junk to template-dependent views
class CalendarTag extends HelperTag
{
	protected $value;
	protected $name;
	protected $js_format;
	protected $wrapper;
	protected $default;
	protected $clear;
	
	protected function validate()
	{
		if ( ! isset($this->js_format)) {
			$this->js_format = 'yyyy-mm-dd';
		}

		if ( ! isset($this->clear)) {
			$this->clear = true;
		}

		if ($this->value instanceof DateTime) {
			$this->default = $this->value = $this->value->format('Y-m-d');
		} elseif (is_string($this->value)) {
			$this->default = $this->value;
		} else {
			$date = new DateTime('today');
			$this->default = $date->format('Y-m-d');
		}

		if ( ! isset($this->wrapper)) {
			throw new ApplicationError('Please specify a wrapper tag to the calendar');
		}
	}
	
	public function idToken()
	{
		return 'cal';
	}
	
	//TODO: create App\Templates\Helpers with views for helpers
	//TODO: clear button in the calendar popup
	public function render($content)
	{
		$clear = $messages = '';
		$alerts = AlertManager::byField($this->name);
		foreach ($alerts as $i => $alert) {
			$messages .= "<span class='help-inline'>{$alert->message()}</span>";
		}

		if ($this->clear) {
			$clear = '<i class="icon-remove" style="vertical-align:middle;cursor:pointer" onclick="this.parentElement.firstElementChild.value=\'\'"></i>';
		}

		$result = <<<EOT
<{$this->wrapper} class="date" data-date="{$this->default}" data-date-format="{$this->js_format}">
	<input type="text" id="{$this->id}" value="{$this->value}" name="{$this->name}"
		class="uneditable-input input-small {$this->class}" type="text"
		style="vertical-align:middle;text-align:center;{$this->style}" disabled>
	<i class="icon-calendar add-on" style="vertical-align:middle;cursor:pointer"></i>
	{$clear}
	{$messages}
</{$this->wrapper}>
EOT;
		
		return $result;
	}
}
