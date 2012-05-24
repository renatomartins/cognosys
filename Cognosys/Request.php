<?php
namespace Cognosys;
use Cognosys\Exceptions\ApplicationError;

/**
 * Request parses the URL
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Request
{
	/**
	 * @var string
	 */
	private $_url;
	
	/**
	 * @var string
	 */
	private $_method;
	
	/**
	 * @var string
	 */
	private $_accept;
	
	/**
	 * @var bool
	 */
	private $_ajax;
	
	/**
	 * @var string
	 */
	private $_protocol;
	
	/**
	 * Known subdomain, generated from matching the host with the path root
	 * @var string
	 */
	private $_subdomain;
	
	/**
	 * @var string
	 */
	private $_host;
	
	/**
	 * @var string
	 */
	private $_path;
	
	/**
	 * @var array
	 */
	private $_query;
	
	/**
	 * @var string|null
	 */
	private $_fragment;
	
	/**
	 * Parses the requested URL in $_SERVER into host, path, query and
	 * splits it with parse_url function, taking into account the path root
	 * @param string $path_root[optional] the path where the application is
	 * available: if $path_root == 'intra.domain.tld/app/' and the current
	 * request 'admin.intra.domain.tld/app/home' then 'admin' is the subdomain
	 * and '/home/' is the path
	 * @throws Cognosys\Exceptions\ApplicationError
	 */
	public function __construct($path_root = null)
	{
		$full_url = $this->_generateProtocol() . $_SERVER['HTTP_HOST']
			. $_SERVER['REQUEST_URI'];
		$this->_url = $full_url;
		
		$url = parse_url($full_url);
		$this->_protocol = $url['scheme'] . '://';
		$this->_host = $url['host'];
 		$this->_path = $this->_normalizePath($url['path']);
		$this->_fragment = isset($url['fragment']) ? $url['fragment'] : '';
		
		if (isset($url['query'])) {
			parse_str($url['query'], $this->_query);
		} else {
			$this->_query = array();
		}
		
		$this->_chopPathRoot($path_root);
		
		$this->_setTypes();
	}
	
	/**
	 * The complete request URL
	 * @return string
	 */
	public function url()
	{
		return $this->_url;
	}
	
	/**
	 * The method of the request, usually GET, POST, PUT or DELETE
	 * @return string
	 */
	public function method()
	{
		return $this->_method;
	}
	
	/**
	 * The accept type of the request, 'html' or 'json'
	 * @return string
	 */
	public function accept()
	{
		return $this->_accept;
	}
	
	/**
	 * The protocol of this request
	 * @return string
	 * @example http://sub.domain.tld/ => "http://"
	 */
	public function protocol()
	{
		return $this->_protocol;
	}
	
	/**
	 * The subdomain of this request, depends on $path_root given to constructor
	 * @return string
	 * @example given "sub.domain.tld" as $path_root and current URL is
	 * http://admin.sub.domain.tld, returns "admin", else ""
	 */
	public function subdomain()
	{
		return $this->_subdomain;
	}
	
	/**
	 * The host of this request with the protocol prepended
	 * @return string
	 * @example http://sub.domain.tld/path => "http://sub.domain.tls"
	 */
	public function host()
	{
		return $this->_protocol . $this->_host;
	}
	
	/**
	 * The path of this request, depends on $path_root given to constructor
	 * @return string
	 * @example http://domain.tld/path/to/somewhere/ => "/path/to/somewhere/"
	 * but given "domain.tld/path" as $path_root and current URL is
	 * http://domain.tld/path/to/somewhere/, returns "/to/somewhere/"
	 * 
	 */
	public function path()
	{
		return $this->_path;
	}
	
	/**
	 * The query of this request
	 * @return array
	 * @example http://domain.tld/path/?a=1&b=2 => array('a' => '1', 'b' => '2')
	 */
	public function query()
	{
		return $this->_query;
	}
	
	/**
	 * The fragment of this request, not available through browser call
	 * @return string|null
	 * @example http://domain.tld/path/#example => "example"
	 */
	public function fragment()
	{
		return $this->_fragment;
	}
	
	/**
	 * Returns if this request has a post or not
	 * @return bool
	 */
	public function post()
	{
		return $this->_post;
	}
	
	public function __toString()
	{
		return
			'Method: ' . $this->_method . "\n" .
			'Protocol: ' . $this->_protocol . "\n" .
			'Host: ' . $this->_host . "\n" .
			'Path: ' . $this->_path . "\n" .
			'Query: ' . join(', ', $this->_query);
	}
	
	/**
	 * Which protocol (URL scheme): 'http://' or 'https://'
	 * @return string
	 */
	private function _generateProtocol()
	{
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
			return 'https://';
		} else {
			return 'http://';
		}
	}
	
	/**
	 * Chops the current path and sets subdomain if needed
	 * @param string|null $path_root
	 * @return void
	 * @throws Cognosys\Exceptions\ApplicationError
	 */
	private function _chopPathRoot($path_root = null)
	{
		if (is_null($path_root)) {
			return;
		}
		
		$path_root = $this->_normalizePath($path_root);
		$path_root = $this->_normalizeProtocol($path_root);
		$path_root = parse_url($path_root);
		
		$pos = strpos($this->_host, $path_root['host']);
		if ($pos === 0 
			&& strlen($this->_host) === strlen($path_root['host'])
		) {
			$this->_subdomain = '';
		} elseif ($pos > 0) {
			$this->_subdomain = substr($this->_host, 0, $pos-1);
		} else {
			throw new ApplicationError(
				'The application subdomain in the configuration '
				. 'does not match real subdomain'
			);
		}
		
		if (strpos($this->_path, $path_root['path']) === 0) {
			$this->_path = substr($this->_path, strlen($path_root['path'])-1);
		} else {
			throw new ApplicationError(
				'The application path in the configuration '
				. 'does not match real path'
			);
		}
	}
	
	/**
	 * Sets _method (GET, POST, PUT or DELETE), _accept (html or json) and
	 * _ajax (if is an AJAX request also chops path to remove /ajax/)
	 * @return void
	 */
	private function _setTypes()
	{
		$this->_method = $_SERVER['REQUEST_METHOD'];
		$this->_accept = strpos($_SERVER['HTTP_ACCEPT'], 'json') > 0
			? 'json'
			: 'html';
		
		$this->_ajax = strpos($this->_path, '/ajax/') === 0;
		if ($this->_ajax) {
			$this->_path = substr($this->_path, 5);
		}
	}
	
	/**
	 * Returns the same URL with an ending slash
	 * @param string $url
	 * @return string
	 */
	private function _normalizePath($url)
	{
		return ($url[strlen($url)-1] !== '/') ? $url . '/' : $url;
	}
	
	/**
	 * Returns the same URL with prepended protocol (schema)
	 * @param string $url
	 * @return string
	 */
	private function _normalizeProtocol($url)
	{
		return (strpos($url, $this->_protocol) !== 0)
			? $this->_protocol . $url
			: $url;
	}
}
