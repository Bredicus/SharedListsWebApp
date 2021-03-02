<?php
class App
{
	var $controller = 'HomeController';
	var $method = 'index';
	var $params = [];
	
	public function __construct() {
		$url = $this->parseUrl();
		
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]).'Controller';
            unset($url[0]);
        }
		
		require_once '../app/controllers/' . $this->controller . '.php';
		
		$this->controller = new $this->controller;

		if (isset($url[1]) && method_exists($this->controller, $url[1])) {
			$this->method = $url[1];
			unset($url[1]);
		}
		
		$this->params = $url ? array_values($url) : [];

		if ($redirectUrl = self::redirectFilters($this->controller, $this->method, $this->params)) {
			header("location:/public/$redirectUrl");
			return;
		}
		
		call_user_func_array([$this->controller, $this->method], $this->params);
	}
	
	public function parseUrl()
	{
		if (isset($_GET['url'])) {
			return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}

	private static function redirectFilters($class, $method, $params) {
		$reflection = new ReflectionClass($class);

		$classDocComment = $reflection->getDocComment();
		$methodDocComment = $reflection->getMethod($method)->getDocComment();

		$classFilters = self::getFiltersFromAnnotations($classDocComment);
		$methodFilters = self::getFiltersFromAnnotations($methodDocComment);

		$filters = array_values(array_filter(array_merge($classFilters, $methodFilters)));

		$redirect = self::runFilters($filters, $params);

		return $redirect;
	}

	private static function getFiltersFromAnnotations($docComment) {
		preg_match('/@accessFilter:{(?<content>.+)}/i', $docComment, $content);
		$content = (isset($content['content']) ? $content['content'] : '');
		$content = explode(',', str_replace(' ', '', $content));
		return $content;
	}

	private static function runFilters($filters, $params) {
		$redirect = false;
		$max = count($filters);
		$i = 0;
		while(!$redirect && $i < $max) {
			if (method_exists('Filter', $filters[$i])) {
				$redirect = Filter::{$filters[$i]}($params);
			}
			else {
				throw new Exception("No policy named $filters[$i]");
			}
			$i++;
		}
		return $redirect;
	}
}
?>