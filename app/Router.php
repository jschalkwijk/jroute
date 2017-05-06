<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 22-04-17
     * Time: 14:43
     */

    namespace App;
    use App\Exceptions\RouteNotFoundException;
    use App\Exceptions\MethodNotAllowedException;

    class Router
    {
        public $path;
        public $routes = [];
        public $methods = [];
        public $params;
        public $bindings = [];
        public $test;
        public $uri;
        public $group;
        public $prefix;
        public $match;

        protected $pattern = [
            ':id' => '\d',
            ':name' => '[a-zA-Z0-9-_.]',
            ':num' => '[0-9]',
            ':alpha' => '[a-zA-Z]',
            ':alphaNum' => '[a-zA-Z0-9]',
        ];

        public function setPath($path)
        {
            $this->path = $path;
            $this->params();

        }
        public function addRoute($uri,$handler, array $methods = [])
        {
            if(!empty($this->prefix)){
                $uri = $this->prefix.$uri;
            }
            $this->routes[$uri] = $handler;
            $this->methods[$uri] = $methods;
            $this->bindings[$uri] = $this->parseUrl($uri);
        }

        public function getResponse()
        {
            foreach ($this->bindings as $key => $value) {
                $string = "/" . ltrim(implode("\/+", $value), "\/+") . "+$/";
                $this->match = $string;
                if (preg_match($string, $this->path)) {

                    $this->params = array_combine(
                        str_replace(
                            ':','',
                            array_flip(
                                $this->bindings[$key]
                            )
                        ),
                        $this->params
                    );

                    if (!in_array($_SERVER['REQUEST_METHOD'], $this->methods[$key])) {
                        throw new MethodNotAllowedException('Method not Allowed!');
                    }

                    return $this->routes[$key];
                }
            }

            throw new RouteNotFoundException('No route found!');
        }

        private function params(){
            if(isset($this->path)){
                // get the URL from the base defined in the.htaccess file.
                // filter url
                $url = filter_var(trim($this->path),FILTER_SANITIZE_URL);
                // delete last / if it is there.
                $url = rtrim($url,'/');
                // create array with all the url parts.
                $url = explode('/',$url);
                // add all array values to the class var routes.
                foreach($url as $key => $value){
                    $this->params[$key] = $value;
                }
            }
        }

        private function parseUrl($uri){
            $bindings = [];

            if(isset($uri)){
                // get the URL from the base defined in the.htaccess file.
                // filter url
                $url = filter_var(trim($uri),FILTER_SANITIZE_URL);
                // delete last / if it is there.
                $url = rtrim($url,'/');
                // create array with all the url parts.
                $url = explode('/',$url);
                // add all array values to the class var routes.

                foreach($url as $key => $value){
                    if(!empty($key) && !empty($value)) {
                        $bindings[$value] = $value;
                        if (isset($this->pattern[$value])) {
                            $bindings[$value] = $this->pattern[$value];
                        }
                    }
                }
            }
            return $bindings;
        }
    }