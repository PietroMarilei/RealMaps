<?php

namespace Driver\BackEnd;


use Driver\BackEnd\Controllers\GetDataController;

class App {
    public static function init($requestUri){
        // se l'url contine getgata chiamo getdata
        $controller = null;
        if ($requestUri ==  "/GetData") {
            $controller = new GetDataController();
        } 

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $controller->run(); 
       }


}