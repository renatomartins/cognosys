<?php
use Cognosys\Config,
	Cognosys\Templates\Decorator,
	Cognosys\Helpers\FormTag;

// in order to have access to controller instance in the functions above
class Helper
{
	static public $controller;
}

//$helper = new Helper();
//var_dump('alo', $helper);

function alerts()
{
	$alerts = Helper::$controller->alert();
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

function view()
{
	print Helper::$controller->view()->content();
}

//TODO: load css files
function loadCss()
{
	//Helper::$controller->view()->
}

//TODO: load javascript files
function loadJs()
{
	$decorator = Helper::$controller->view()->getDecorator();
	if ( ! $decorator instanceof Decorator) {
		return;
	}

	foreach (Config::get("templates/options/{$decorator->name()}/js") as $js) {
		
	}
}

function form($content, array $params = array())
{
	return FormTag::create(Helper::$controller, $params, $content);
}

//TODO: other functions to render other tags
//TODO: call custom renderers, like gallery or dates renderers
