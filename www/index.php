<?
require_once __DIR__ . '/bootstrap.php';

use FastRoute\RouteCollector;
use \RedBeanPHP\R as R;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
	$r->addGroup('/api', function (RouteCollector $r) {
		//Для всей группы возвращаем json
		header('Content-type: application/json');

		//Категории
		$r->addGroup('/category', function (RouteCollector $r) {
			//По id категории
			$r->addGroup('/{category_id:\d+}', function (RouteCollector $r) {
				//Вопросы в категории
				$r->addGroup('/questions', function (RouteCollector $r) {
					//Все
					$r->addRoute('GET', '/all/', function ($categoryId) {
						$questions = R::findAll(QUESTIONS_TABLE, 'category_id = ?', [$categoryId]);
						echo json_encode($questions);
					});
					//Добавленные за последние 7 дней
					$r->addRoute('GET', '/recently/', function ($categoryId) {
						$startDate = (new DateTime())->modify('-7 days');
						$questions = R::findAll(QUESTIONS_TABLE, 'category_id = ? and created >= ?', [$categoryId, $startDate->format('Y-m-d 00:00:00')]);
						echo json_encode($questions);
					});
				});
			});
		});
	});
});

$routeHandler = new App\FastRouteWrap();
$routeHandler->dispatch($dispatcher);
