<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 22-04-17
     * Time: 14:43
     */

    namespace App;
    use App\Exceptions\MethodNotAllowedException;
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

        public function setPath($path)
        {
            $this->path = $path;
            $this->params();

        }
        public function addRoute($uri,$handler, array $methods = [])
        {
            $this->routes[$uri] = $handler;
            $this->methods[$uri] = $methods;
            $this->parseUrl($uri);
        }

        public function getResponse()
        {
            if(!empty($this->parts)){
                if(in_array(":id",$this->parts)){
                    $this->test = preg_match("/[a-zA-Z]\/+[0-9]/",$this->path);
                }
                if (!$this->test){
                    throw new RouteNotFoundException();
                }
            }
//            if (!isset($this->routes[$this->path])){
//                throw new RouteNotFoundException('No route found!');
//            }

//            if (!in_array($_SERVER['REQUEST_METHOD'],$this->methods[implode("/",$this->parts)])){
//                throw new MethodNotAllowedException;
//            }
            //return $this->routes[$this->path];
            return $this->routes[implode("/",$this->parts)];
        }
        private function params(){
            if(isset($this->path)){
                // get the URL from the base defined in the.htaccess file.
                // filter url
                $url = filter_var(trim($this->path),FILTER_SANITIZE_URL);
                // delete last / if it is there.
                $url = rtrim($url,'/');
                // Remove the - (dash) in the url : EX. admin/add-user. Classnames can't have the - (dash) so class is written as AddUser.
                // to call the function we need to remove the - (dash)
                $url = str_replace('-','', $url );
                // create array with all the url parts.
                $url = explode('/',$url);
                // add all array values to the class var routes.
                foreach($url as $key => $value){
                    $this->params[$this->parts[$key]] = $value;
                }
            }
        }
//         TODO: zie scratch_6, we moeten $this->parts, veranderen anders wordt hij steeds gereset, maar hoe kom ik er weer bij dan?
//          foreach($url as $key => $value){
//              $parts[$key] = $value;
//          }
//          return $parts;
        private function parseUrl($uri){
            if(isset($uri)){
                // get the URL from the base defined in the.htaccess file.
                // filter url
                $url = filter_var(trim($uri),FILTER_SANITIZE_URL);
                // delete last / if it is there.
                $url = rtrim($url,'/');
                // Remove the - (dash) in the url : EX. admin/add-user. Classnames can't have the - (dash) so class is written as AddUser.
                // to call the function we need to remove the - (dash)
                $url = str_replace('-','', $url );
                // create array with all the url parts.
                $url = explode('/',$url);
                // add all array values to the class var routes.
                foreach($url as $key => $value){
                    $this->parts[$key] = $value;
                }
            }
        }
    }