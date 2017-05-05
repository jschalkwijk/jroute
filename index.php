<?php
	ob_start();
	require 'vendor/autoload.php';

	$app = new App\App;

	$container = $app->getContainer();

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


	$app->get('/', [new App\Controllers\HomeController, 'index']);
	$app->group('/admin',function($app){
		$app->group('/users',function($app,$container){
			$app->get('', [new App\Controllers\UserController($container->db), 'index']);
			$app->get('/edit/:id', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/create', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/delete', [new App\Controllers\UserController($container->db), 'one']);
		});
		$app->group('/posts',function($app,$container){
			$app->get('', [new App\Controllers\UserController($container->db), 'index']);
			$app->get('/edit/:id', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/create', [new App\Controllers\UserController($container->db), 'one']);
			$app->get('/delete', [new App\Controllers\UserController($container->db), 'one']);
		});
	});

	$app->run();
	ob_end_flush();


