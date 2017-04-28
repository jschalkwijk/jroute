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
        public $parts = [];
        public $test;
        public $uri;

        protected $pattern = [
            ':id' => '\d',
            ':name' => '[a-zA-Z]',
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
            $this->routes[$uri] = $handler;
            $this->methods[$uri] = $methods;
            $this->parts[$uri] = $this->parseUrl($uri);
        }

        public function getResponse()
        {
            foreach ($this->parts as $key => $value) {
                $string = "/" . ltrim(implode("\/+", $value), "\/+") . "+$/";
                if (preg_match($string, $this->path)) {
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
            $parts = [];

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
                    $parts[$value] = $value;
                    if(isset($this->pattern[$value])) {
                        $parts[$value] = $this->pattern[$value];
                    }
                }
                return $parts;
            }
        }
    }