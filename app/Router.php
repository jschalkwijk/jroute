<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 22-04-17
     * Time: 14:43
     */

    namespace App;
    use App\Exceptions\RouteNotFoundException;

    class Router
    {
        public $path;
        public $routes = [];
        protected $methods = [];
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
            foreach($this->parts as $key => $value){
                $string = "/".ltrim(implode("\/+",$value),"\/+")."+$/";
                if (preg_match($string, $this->path)) {
                    return $this->routes[$key];
                }
            }
            throw new RouteNotFoundException('No route found!');


//            foreach($this->parts as $key => $value){
//                $string = "/".implode("\/+",$value)."/";
//                if(preg_match($string,$this->path)){
//                    return [$this->routes[$key]];
//                }
//
//                //preg_match("/[a-zA-Z]\/+[0-9]/",$this->path);
//            }
//            foreach($this->routes as $key => $value){
//                if($key == implode("/",$this->parts[$key])){
//
//                    $route = $this->parts[$key];
//                    if(!empty($this->parts[$key])){
//                        if(in_array(":id",$this->parts[$key])){
//                            $this->test = preg_match("/[a-zA-Z]\/+[0-9]/",$this->path);
//                        }
//                        if (!$this->test){
//                            throw new RouteNotFoundException();
//                        }
//                    }
//
//                    return [$this->routes[implode("/",$route)]];
//                }
//
//            }

//            if (!isset($this->routes[$this->path])){
//                throw new RouteNotFoundException('No route found!');
//            }

//            if (!in_array($_SERVER['REQUEST_METHOD'],$this->methods[implode("/",$this->parts)])){
//                throw new MethodNotAllowedException;
//            }
            //return $this->routes[$this->path];
//            return $this->routes[implode("/",$this->route)];
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