<?php
    /*
   |--------------------------------------------------------------------------
   | Define the app routes here.
   |--------------------------------------------------------------------------
   */
   /*
    * Note that the group method can go down for 2 levels for now.
    * If you need a third level, register it as a second level and add to it.
    *
    */

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
    });