<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use FastRoute\RouteCollector;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
	$r->addGroup('/api', function (RouteCollector $r) {

	});
});

$routeHandler = new App\FastRouteWrap();
$routeHandler->dispatch($dispatcher);
