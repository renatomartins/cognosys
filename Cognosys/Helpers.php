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

function alerts()
{
	$alerts = Helpers::$controller->alert();
	if (count($alerts) === 0) {
		return;
	}

	foreach ($alerts as $type => $elements) {
		$result = "cognosys.alert(['";

		$result .= join("','", array_map(function($alert) {
			return addslashes($alert->message());
		}, $elements));

		print $result . "'], '{$type}')\n";
	}
}

function js()
{

}

function css()
{

}

//TODO: other functions to render other tags

//TODO: call custom renderers, like gallery or dates renderers
