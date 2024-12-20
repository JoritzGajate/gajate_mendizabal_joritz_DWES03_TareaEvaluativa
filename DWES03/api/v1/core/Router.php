<?php

class Router{

    protected $routes = array();
    protected $params = array();

    public function add($route, $params){
        $this->routes[$route] = $params;
    }

    public function getRoutes(){
            return $this->routes;
    }

    public function matchRoute($url){
        foreach($this->routes as $route=>$params)
        {
            $pattern = str_replace(['{id}','/'], ['([0-9]+)', '\/'], $route);
            $pattern = '/^'.$pattern.'$/';

            if(preg_match($pattern, $url['path'])){
                $this->params=$params;
                return true;
            }
        }

        //Si la ruta no es igual a ninguna que hayamos configurado lanza la respuesta con el error
        $this->sendNotFoundError();
        return false;
    }

    //Respuesta de error de ruta no definida
    public function sendNotFoundError() {
        http_response_code(404);
        echo json_encode([
            'status' => 'ERROR',
            'code' => '404',
            'response' => 'Ruta no definida'
        ]);
    }

    public function getParams(){
        return $this->params;
    }

}