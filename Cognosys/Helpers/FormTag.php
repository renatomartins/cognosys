<?php
namespace Cognosys\Helpers;
use Cognosys\Session,
	Cognosys\TextUtil,
	Cognosys\Exceptions\ApplicationError,
	\Closure;

/**
 * Helper to render a form element tag
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class FormTag extends HelperTag
{
	protected $accept_charset;
	protected $action;
	protected $enctype;
	protected $method;
	
	protected function validate()
	{
		if (isset($this->accept_charset) === false) {
			$this->accept_charset = 'utf-8';
		}
		if (isset($this->action) === false) {
			$this->action = $this->template()->request()->url();
		}
		if (isset($this->method) === false) {
			$this->method = 'post';
		}
		if ($this->content() instanceof Closure === false) {
			throw new ApplicationError(
				'Form element must have an anonymous function with content'
			);
		}
	}
	
	public function idToken()
	{
		return 'frm';
	}
	
	public function render($content)
	{
		ob_start();
		$content($this);
		$content = ob_get_clean();
		
		$authtoken = md5(uniqid(rand(), true));
		Session::instance()->set('_authtoken_', $authtoken);
		
		return "<form {$this->getAttributes()}>"
			. $this->hidden('_authtoken_', $authtoken)
			. $content
			. '</form>';
	}
	
	public function input($type, $field, $params = array())
	{
		return InputTag::create($this->template(), array_merge(array(
			'type'	=> $type,
			'id'	=> "{$this->id}-{$field}",
			'name'	=> $field
		), $params));
	}
	
	public function text($field, $params = array())
	{
		return $this->input('text', $field, $params);
	}
	
	public function password($field, $params = array())
	{
		return $this->input('password', $field, $params);
	}
	
	//TODO: does this receive a closure with multiple checkboxes?
	public function checkbox($field, $checked = false, $params = array())
	{
		return $this->input('checkbox', $field, array_merge(array(
			'checked'	=> $checked
		), $params));
	}
	
	//TODO: does this receive a closure with multiple radio buttons?
	public function radio($field, $value, $checked = false, $params = array())
	{
		return $this->input('radio', $field, array_merge(array(
			'value'		=> $value,
			'checked'	=> $checked
		), $params));
	}
	
	public function submit($value = 'Submit', $params = array())
	{
		return $this->input('submit', 'submit', array_merge(array(
			'value'	=> $value
		), $params));
	}
	
	public function reset($params)
	{
		return $this->input('reset', 'reset', $params);
	}
	
	public function file($field, $params = array())
	{
		$this->_enctype = 'multipart/form-data';
		return $this->input('file', $field, $params);
	}
	
	public function hidden($field, $value, $params = array())
	{
		return $this->input('hidden', $field, array_merge(array(
			'value'	=> $value
		), $params));
	}
	
	private $_btn_count = 0;
	public function button($field, $value = null, $params = array())
	{
		++$this->_btn_count;
		if ($value === null) {
			$value = TextUtil::humanize($field);
		}
		
		return $this->input('button', $field.$this->_btn_count, array_merge(array(
			'value'	=> $value
		), $params));
	}
	
	/**
	 * Creates a textarea relative to this form
	 * @param string $field
	 * @param array $params
	 */
	public function textarea($field, $text = '', $params = array())
	{
		return TextareaTag::create($this->template(), array_merge(array(
			'id'	=> "{$this->id}-{$field}",
			'name'	=> $field
		), $params), $text);
	}
	
	public function label($for, $content = null, $params = array())
	{
		return LabelTag::create($this->template(), array_merge(array(
			'id'	=> "{$this->id}-lbl-{$for}",
			'for'	=> $for	//TODO: 'for' attribute must match id of the input
		), $params), $content);
		
#		if ($text === null) {
#			$text = TextUtil::humanize($for);
#		}
#		return "<label id='lbl-{$this->_id}-{$for}' for='{$for}'>{$text}</label>";
	}
	
	public function select($field, $content = null, $params = array())
	{
		return SelectTag::create($this->template(), array_merge(array(
			'id'		=> "{$this->id}-slt-{$field}",
			'name'		=> $field
		), $params), $content);
	}

	public function calendar($field, $value, array $params = array())
	{
		return CalendarTag::create($this->template(), array_merge(array(
			'id'	=> "{$this->id}-cal-{$field}",
			'name'	=> $field
		), $params));
	}
	
	/*
	public function custom($field, )
	{
		//TODO: call a custom helper to render a group of tags
		// image galleries, dates, etc..
		echo "custom input '$name': ";
		var_dump($args);
		echo "<br>";
	}
	*/
}
