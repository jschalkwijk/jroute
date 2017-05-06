<?php
	/*
		|--------------------------------------------------------------------------
		| Register The Auto Loader
		|--------------------------------------------------------------------------
	*/
	require 'vendor/autoload.php';
	/*
		|--------------------------------------------------------------------------
		| Start the app
		|--------------------------------------------------------------------------
	*/
	$app = require 'app/loader/loader.php';
	/*
		|--------------------------------------------------------------------------
		| Get the user defined routes
		|--------------------------------------------------------------------------
	*/

	$container = $app->getContainer();

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




