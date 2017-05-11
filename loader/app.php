<?php
    /*
    |--------------------------------------------------------------------------
    | Starting the App.
    |--------------------------------------------------------------------------
    */
    /*
     * Start filling the Container with dependencies.
     * Feed the container to the new App instance.
     * Return the app.
     */
    use App\Container;
    use App\Router;
    use App\Response;

    $container =  new Container([
        'router' => function () {
            return new Router;
        },
        'response' => function () {
            return new Response;
        },
        'errorHandler' => function () {
            return function ($response) {
                return $response->setBody('Page not found')->withStatus(404);
            };
        },

        'config' => function () {
            return require 'config/database.php';
        },

        'db' => function ($c) {
            return new \PDO(
                $c->config['db_driver'] . ':host=' . $c->config['db_host'] . ';dbname=' . $c->config['db_name'],
                $c->config['db_user'],
                $c->config['db_pass']
            );
        },
    ]);

    $app = new App\App($container);

    return $app;