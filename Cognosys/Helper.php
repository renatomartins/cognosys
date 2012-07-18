<?php
use Cognosys\AlertManager,
	Cognosys\Config,
	Cognosys\Session,
	Cognosys\TextUtil,
	Cognosys\Widget,
	Cognosys\Templates\Decorator,
	Cognosys\Helpers\FormTag,
	Cognosys\Helpers\InputTag,
	Cognosys\Helpers\CalendarTag,
	\DateTime;

// in order to have access to template instance in the functions below
class Helper
{
	static private $_templates = array();

	static public function template()
	{
		return end(self::$_templates);
	}

	static public function addTemplate($template)
	{
		self::$_templates[] = $template;
	}

	static public function removeTemplate()
	{
		array_pop(self::$_templates);
	}
}


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

function bool($value)
{
	$which = $value ? 'ok' : 'remove';
	return "<div class='center'><i class='icon-{$which}'></i></div>";
}

function datetime($value, $format = 'Y-m-d')
{
	return $value instanceof DateTime ? $value->format($format) : $value;
}

function url($url = '', $ajax = false)
{
	$ajax = $ajax ? '/ajax' : '';
	if (strpos($url, 'http://') === 0 || strpos($url, '//') === 0) {
		return $url;
	} elseif ( ! empty($url) && $url[0] === '/') {
		return Helper::template()->request()->host() . $ajax . $url;
	} else {
		return Helper::template()->request()->host() . $ajax . '/'
			. Helper::template()->response()->originalController() . '/'
			. $url;
	}
}

function ajax($url = '', $load = false, $type = null)
{
	$url = url($url, true);
	$load = $load ? ' load' : '';
	$type = is_null($type) ? '' : " type='{$type}'";
	return "<ajax href='{$url}'{$load}{$type}></ajax>";
}

function json($url)
{
	return ajax($url, true, 'json');
}

function view()
{
	print Helper::template()->content();
}

function inject($filename)
{
	$file = COGS . Helper::template()->response()->cog()
		. '/Views/' . Helper::template()->response()->originalController()
		. '/' . $filename . '.php';

	if (is_readable($file)) {
		extract(Helper::template()->getVariables());
		include $file;
	}
}

//TODO: load css files in config
//TODO: get from config the root for css files
function loadCssFiles()
{
	$files = Helper::template()->getCssFiles();
	$links = '';
	foreach ($files as $file) {
		$links .= "<link rel='stylesheet' href='/css/{$file}'>\n";
	}
	return $links;
}

function loadCssSnippets()
{
	$snippets = Helper::template()->getCssSnippets();
	return join("\n", $snippets) . "\n";
}

//TODO: load javascript files in config
//TODO: get from config the root for js files
function loadJsFiles()
{
	$files = Helper::template()->getJsFiles();
	$scripts = '';
	foreach ($files as $file) {
		$scripts .= "<script src='/js/{$file}'></script>\n";
	}
	return $scripts;
}

function loadJsSnippets()
{
	$snippets = Helper::template()->getJsSnippets();
	return join("\n", $snippets) . "\n";
}

function cssFile($filename)
{
	Helper::template()->addCssFile($filename);
}

function cssSnippet($snippet)
{
	Helper::template()->addCssSnippet($snippet);
}

function jsFile($filename)
{
	Helper::template()->addJsFile($filename);
}

function jsSnippet($snippet)
{
	Helper::template()->addJsSnippet($snippet);
}

function widget($cog_dashed, $widget_dashed, $params = array())
{
	$widget = Widget::factory($cog_dashed, $widget_dashed, $params);
	return $widget->render();
}

function form($content, array $params = array())
{
	return FormTag::create(Helper::template(), $params, $content);
}

function input($id, $value = '', array $params = array())
{
	return InputTag::create(Helper::template(), array_merge(array(
		'type'	=> 'text',
		'id'	=> $id,
		'value'	=> $value
	), $params));
}

function calendar($id, $value = '', $wrapper = 'div', array $params = array())
{
	return CalendarTag::create(Helper::template(), array_merge(array(
		'id'		=> $id,
		'value'		=> $value,
		'wrapper'	=> $wrapper
	), $params));
}
