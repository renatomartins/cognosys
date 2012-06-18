<?php
use Cognosys\AlertManager,
	Cognosys\Config,
	Cognosys\Templates\Decorator,
	Cognosys\Helpers\FormTag;

// in order to have access to controller instance in the functions above
class Helper
{
	//static public $controller;
	static public $template;
}

//$helper = new Helper();
//var_dump('alo', $helper);

function alerts()
{
	//$alerts = Helper::$controller->alert();
	$alerts = AlertManager::byType();
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

function url($url = '', $ajax = false)
{
	$ajax = $ajax ? '/ajax' : '';
	if (strpos($url, 'http://') === 0 || strpos($url, '//') === 0) {
		return $url;
	} elseif ( ! empty($url) && $url[0] === '/') {
		return Helper::$template->request()->host() . $ajax . $url;
	} else {
		return Helper::$template->request()->host() . $ajax . '/'
			. Helper::$template->response()->originalController() . '/'
			. $url;
	}
}

function ajax($url = '', $load = false)
{
	$url = url($url, true);
	$load = $load ? ' load' : '';
	return "<ajax href='{$url}'{$load}></ajax>";
}

function view()
{
	print Helper::$template->content();
}

function inject($filename)
{
	$file = COGS . Helper::$template->response()->cog()
		. '/Views/' . Helper::$template->response()->originalController()
		. '/' . $filename . '.php';

	if (is_readable($file)) {
		extract(Helper::$template->getVariables());
		include $file;
	}
}

//TODO: load css files
function loadCss()
{
	//Helper::$controller->view()->
}

//TODO: load javascript files, $template instead of $controller
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
	return FormTag::create(Helper::$template, $params, $content);
}

//TODO: other functions to render other tags
//TODO: call custom renderers, like gallery or dates renderers
