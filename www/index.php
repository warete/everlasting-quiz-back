<?
require_once __DIR__ . '/bootstrap.php';

use FastRoute\RouteCollector;
use \RedBeanPHP\R as R;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
	$r->addGroup('/api', function (RouteCollector $r) {
		$r->addRoute('GET', '/users', function () {

		});
	});
});

$routeHandler = new App\FastRouteWrap();
$routeHandler->dispatch($dispatcher);
