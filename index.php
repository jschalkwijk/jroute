<?php
	ob_start();
	require 'vendor/autoload.php';
	use App\Container;
	use App\Router;
	use App\Response;

	$container =  new Container;
	$container['router'] = function () {
			return new Router;
		};
		$container['response'] = function () {
			return new Response;
		};

	$container['errorHandler'] = function () {
		return function ($response) {
			return $response->setBody('Page not found')->withStatus(404);
		};
	};

	$container['config'] = function () {
		return [
			'db_driver' => 'mysql',
			'db_host' => 'localhost',
			'db_name' => 'nerdcms_db',
			'db_user' => 'root',
			'db_pass' => 'root',
		];
	};

	$container['db'] = function ($c) {
		return new PDO(
			$c->config['db_driver'] . ':host=' . $c->config['db_host'] . ';dbname=' . $c->config['db_name'],
			$c->config['db_user'],
			$c->config['db_pass']
		);
	};

	$app = new App\App($container);

	$app->get('/', [new App\Controllers\HomeController, 'index']);
	$app->group('/admin',function($app){
		$app->group('/users',function($app,$container){
			$app->get('', [new App\Controllers\UserController($container->db), 'index']);
			$app->get('/edit/:id', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/create', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/delete', [new App\Controllers\UserController($container->db), 'one']);
		});
		$app->group('/users/profile',function($app,$container){
			$app->get('/delete', [new App\Controllers\UserController($container->db), 'index']);

		});
		$app->group('/posts',function($app,$container){
			$app->get('', [new App\Controllers\UserController($container->db), 'index']);
			$app->get('/edit/:id', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/create', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/delete', [new App\Controllers\UserController($container->db), 'one']);
			$app->group('/posts/profile',function($app,$container){
				$app->get('/delete', [new App\Controllers\UserController($container->db), 'index']);

			});
		});
		$app->group('/test',function($app,$container){
			$app->get('', [new App\Controllers\UserController($container->db), 'index']);
			$app->get('/edit/:id', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/create', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/delete', [new App\Controllers\UserController($container->db), 'one']);
			$app->group('/test/profile',function($app,$container){
				$app->get('/delete', [new App\Controllers\UserController($container->db), 'index']);

			});
		});
	});

	$app->run();
	echo "<br><br>Container: <br>";
	print_r($container);
	ob_end_flush();


