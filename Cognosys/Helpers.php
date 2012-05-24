<?php
use Cognosys\Helpers\FormTag;

// in order to have access to controller instance in the functions above
class Helpers
{
	/**
	 * @static
	 * @var Cognosys\Controller $controller
	 */
	static public $controller;
}
Helpers::$controller = $this;

function form($content, array $params = array())
{
	return FormTag::create(Helpers::$controller, $params, $content);
}

function alerts(array &$alerts)
{
	$result = '<div id="alerts">';
	foreach ($alerts as $i => $alert) {
		if ($alert->field() === null) {
			$result .= $alert->render();
			unset($alerts[$i]);
		}
	}
	print $result . '</div>';
}

//TODO: other functions to render other tags

//TODO: call custom renderers, like gallery or dates renderers
