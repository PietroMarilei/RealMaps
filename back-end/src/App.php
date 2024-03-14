<?php

namespace Driver\BackEnd;

use Exception;
use Driver\BackEnd\Controllers\Controller;
use Driver\BackEnd\Controllers\GetDataController;
use Driver\BackEnd\Controllers\UploadDataController;
use Driver\BackEnd\Controllers\SearchDataController;
use Driver\BackEnd\Controllers\AnalyzeDataController;
// $routes = [
//     "POST" => [
//         "/GetData" => GetDataController::class,
//     ]
// ]

class App
{
    public static function init($requestUri)
    {
        $uriComponents = explode('?', $requestUri);
        $path = $uriComponents[0];
        //legge sempre la prima parte dell'uri prima del ? della query

        $controller = null;
        // i controller e le rotte devono chiamarsi uguali
        $controllerClass = "\\Driver\\BackEnd\\Controllers\\" . substr($path, 1) . "Controller";

        try {
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (!$controller instanceof Controller) { 
                    throw new Exception("Controller must be an instance of Controller");
                }
            } else {
                throw new Exception("Controller class not found");
            }
        } catch (Exception $err) {
            http_response_code(401);
            echo json_encode(['error' => '401 not found', 'message' => $err->getMessage()]);
        }

        // if ($path === "/GetData") {
        //     $controller = new GetDataController();
        // } else if ($path === "/UploadData") {
        //     $controller = new UploadDataController();
        // } else if ($path === "/SearchData") {
        //     $controller = new SearchDataController();
        // } else if ($path === "/AnalyzeData") {
        //     $controller = new AnalyzeDataController();
        // } else {
        //     http_response_code(404);
        //     echo json_encode(['error' => '404 not found']);
        //     return;
        // }

        ini_set('memory_limit', '256M');

        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        $controller->run();
    }
}
