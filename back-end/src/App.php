<?php
use Driver\BackEnd\Controllers\AnalyzeDataController;
// Importing the AnalyzeDataController class from the Driver\BackEnd\Controllers namespace

class App
{
    public static function init($requestUri)
    {
        // Split the URI to get the path before the query parameters
        $uriComponents = explode('?', $requestUri);
        $path = $uriComponents[0];

        // Initialize the appropriate controller based on the URI path
        if ($path === "/GetData") {
            $controller = new GetDataController();
        } else if ($path === "/UploadData") {
            $controller = new UploadDataController();
        } else if ($path === "/SearchData") {
            $controller = new SearchDataController();
        } else if ($path === "/AnalyzeData") {
            $controller = new AnalyzeDataController();
        } else {
            // Return a 404 error if the path does not match any controller
            http_response_code(404);
            echo json_encode(['error' => '404 not found']);
            return;
        }

        // Server configuration settings
        ini_set('memory_limit', '256M');

        // HTTP response headers configuration
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        // Execute the 'run' method of the selected controller
        $controller->run();
    }
}
