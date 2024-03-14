<?php

namespace Driver\BackEnd;


use Driver\BackEnd\Controllers\GetDataController;
use Driver\BackEnd\Controllers\UploadDataController;
use Driver\BackEnd\Controllers\SearchDataController; 

// $routes = [
//     "POST" => [
//         "/GetData" => GetDataController::class,
//     ]
// ]

class App {
    public static function init($requestUri)
    {
        $uriComponents = explode('?', $requestUri);
        $path = $uriComponents[0];
        //legge sempre la prima parte dellúri prima del ?

        $controller = null;
        if ($path === "/GetData") {
            $controller = new GetDataController();
        } else if ($path === "/UploadData") {
            $controller = new UploadDataController();
        } else if ($path === "/SearchData") { 
            $controller = new SearchDataController();
        }
        
        else {
            http_response_code(404);
            echo json_encode(['error' => '404 not found']);
            return;
        }

        ini_set('memory_limit', '256M');

        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        $controller->run(); 
       }


}