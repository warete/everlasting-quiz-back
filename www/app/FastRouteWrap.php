<?php
namespace App;
use FastRoute\Dispatcher;

/**
 * Class FastRouteWrap
 */
class FastRouteWrap
{
	/**
	 * @param Dispatcher $dispatcher
	 */
	public function dispatch(Dispatcher $dispatcher)
	{
		// Fetch method and URI from somewhere
		$httpMethod = $_SERVER['REQUEST_METHOD'];
		$uri = $_SERVER['REQUEST_URI'];

		// Strip query string (?foo=bar) and decode URI
		if (false !== $pos = strpos($uri, '?')) {
			$uri = substr($uri, 0, $pos);
		}
		$uri = rawurldecode($uri);

		$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
		switch ($routeInfo[0]) {
			case Dispatcher::NOT_FOUND:
				// ... 404 Not Found
				break;
			case Dispatcher::METHOD_NOT_ALLOWED:
				$allowedMethods = $routeInfo[1];
				// ... 405 Method Not Allowed
				break;
			case Dispatcher::FOUND:
				self::callRouteHandler($routeInfo);
				break;
		}
	}

	/**
	 * @param array $routeInfo
	 */
	protected function callRouteHandler(array $routeInfo)
	{
		list($state, $handler, $vars) = $routeInfo;
		if ($handler instanceof \Closure || function_exists($handler))
		{
			call_user_func_array($handler, $vars);
		}
		else
		{
			list($className, $method) = explode('@', $handler);
			if (class_exists($className) && method_exists($className, $method))
			{
				call_user_func_array([$className, $method], $vars);
			}
		}
	}
}
