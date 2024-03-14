<?php

namespace Driver\BackEnd;


use Driver\BackEnd\Controllers\GetDataController;
use Driver\BackEnd\Controllers\UploadDataController;
// $routes = [
//     "POST" => [
//         "/GetData" => GetDataController::class,
//     ]
// ]

class App {
    public static function init($requestUri){
        // se l'url contine getgata chiamo getdata
        $controller = null;
        if ($requestUri ==  "/GetData") {
            $controller = new GetDataController();
        } else if ($requestUri ==  "/UploadData") {
            $controller = new UploadDataController();
        }
        
        else {
            //TODO ritornare errore bello
            echo "404 not found";
        }

        ini_set('memory_limit', '256M');

        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        $controller->run(); 
       }


}