<?php
namespace Cognosys;

/**
 * Static functions with various utilities
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class TextUtil
{
	/**
	 * Removes dashes from string and uppercases next character.
	 * Also uppercases first character
	 * @static
	 * @param string $str
	 * @return string
	 */
	static public function classify($str)
	{
		$str = trim($str, '-');
		
		$result = preg_replace_callback('/(-\w)/', function ($match) {
			return strtoupper($match[1][1]);
		}, $str);
		
		return ucfirst($result);
	}
	
	/**
	 * Like classify, but downcases first character
	 * @static
	 * @param string $str
	 * @return string
	 */
	static public function camelize($str)
	{
		return lcfirst(self::classify($str));
	}
	
	/**
	 * Downcase uppercase characters and insert dashes before
	 * @static
	 * @param string $str
	 * @return string
	 */
	static public function dasherize($str)
	{
		$str = lcfirst($str);
		return strtolower(preg_replace('/([A-Z]+)/', '-$1', $str));
	}
	
	/**
	 * Downcase uppercase characters and insert underscores before
	 * @static
	 * @param string $str
	 * @return string
	 */
	static public function underscore($str)
	{
		$str = lcfirst($str);
		return strtolower(preg_replace('/([A-Z]+)/', '_$1', $str));
	}
	
	/**
	 * Replace dashes and underscores by spaces and capitalizes first character
	 * @param string $str
	 * @return string
	 */
	static public function humanize($str)
	{
		return ucfirst(str_replace(array('-', '_'), ' ', $str));
	}

	/**
	 * Dasherize and remove spaces
	 * @param string $str
	 * @return string
	 */
	static public function dehumanize($str)
	{
		return str_replace(' ', '', self::dasherize($str));
	}

	/**
	 * Insert a certain amount of tabs in each line, except the first
	 * @param string $str
	 * @param int $tabs
	 * @return string
	 */
	static public function tabify($str, $tabs = 1)
	{
		$replace = "\n" . str_repeat("\t", $tabs);
		return str_replace(array("\r\n", "\r", "\n"), $replace, $str);
	}
}
