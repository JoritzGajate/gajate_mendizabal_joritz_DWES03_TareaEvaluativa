<?php

require '../core/Router.php';
require '../app/controllers/Bombones.php';


$queryString = $_SERVER['QUERY_STRING'];

//Extraer el valor de 'url' correctamente
//Hace que el valor sea $url = /public/...
parse_str($queryString, $queryArray);
$url = isset($queryArray['url']) ? $queryArray['url'] : '';

if ($url !== '' && $url[0] !== '/') {
    $url = '/' . $url;
}

$router = new Router();


//Rutas configuradas
$router->add('/public/bombones/get', array(
    'controller'=>'Bombones',
    'action'=>'getAllBombones'
));

$router->add('/public/bombones/get/{id}', array(
    'controller'=>'Bombones',
    'action'=>'getBombonesById'
));

$router->add('/public/bombones/create', array(
    'controller'=>'Bombones',
    'action'=>'createBombon'
));

$router->add('/public/bombones/update/{id}', array(
    'controller'=>'Bombones',
    'action'=>'updateBombon'
));

$router->add('/public/bombones/delete/{id}', array(
    'controller'=>'Bombones',
    'action'=>'deleteBombon'
));

$urlParams = explode('/',$url);

$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>'',
    'action'=>'',
    'params'=>''
);

if(!empty($urlParams[2])){
    $urlArray['controller'] = ucwords($urlParams[2]);
    if(!empty($urlParams[3])){
        $urlArray['action'] = $urlParams[3];
        if(!empty($urlParams[4])){
            $urlArray['params'] = $urlParams[4];
        }
    }else{
        $urlArray['action'] = 'index';
    }

}else{
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}

if ($router->matchRoute($urlArray)){
    $method = $_SERVER['REQUEST_METHOD'];

    $params = [];

    if ($method === 'GET'){
        $params[]=intval($urlArray['params']) ?? null;

    } elseif ($method === 'POST'){

        $json = file_get_contents(('php://input'));
        $params[]=json_decode($json, true);

    } elseif ($method === 'PUT'){
        $id = intval($urlArray['params']) ?? null;
        $json = file_get_contents(('php://input'));
        $params[]=$id;
        $params[]=json_decode($json,true);

    } elseif ($method === 'DELETE'){
        $params[]=intval($urlArray['params']) ?? null;
    }
    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];
    $controller = new $controller();

    if(method_exists($controller,$action)){
        $resp = call_user_func_array([$controller,$action], $params);
    }else{
        echo "El metodo no existe.";
    }
}
