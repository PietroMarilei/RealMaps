<?php

ini_set('memory_limit', '256M');

header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once './db/dbConnector.php';

function sanitizeInput($input) {
    $input = str_replace(',', '-', $input);
    $input = preg_replace("/[^a-zA-Z0-9\s\.\-\_]/", "", $input);
    $input = preg_replace('/\s+/', ' ', $input);
    return $input;
}

function importCsvToDb($filePath, $db) {
    $table = 'patients';
    $fileHandle = fopen($filePath, 'r');
    fgetcsv($fileHandle); // Saltare l'intestazione del CSV.
    $query = "INSERT INTO $table (FirstName, LastName, BirthDate, Email, Symptoms, Diagnosis, Location, DiagnosisDate, PatientStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $counter = 0;
    while (($rowData = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
        $sanitizedRowData = array_map('sanitizeInput', $rowData);
        $stmt->execute($sanitizedRowData);
        $counter++;
    }
    fclose($fileHandle);
    return $counter; // Restituire il conteggio dei record inseriti
}

$response = ['success' => false, 'message' => 'Errore nell\'upload del file.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['csv_file'])) {
        $database = new Database();
        $db = $database->getConnection();
        $filePath = $_FILES['csv_file']['tmp_name'];
        $count = importCsvToDb($filePath, $db);
        $response = ['success' => true, 'message' => "Importazione completata con successo. $count record inseriti."];
    }
} else {
    $response = ['success' => false, 'message' => 'Metodo non supportato.'];
}

echo json_encode($response);


// ini_set('memory_limit', '256M');
// header('Content-Type: application/json');

// require_once './db/dbConnector.php';

// function sanitizeInput($input)
// {
    
//     $input = str_replace(',', ' ', $input);
    
//     $input = preg_replace("/[^a-zA-Z0-9\s\.\-\_]/", "", $input);

//     $input = preg_replace('/\s+/', ' ', $input);

//     return $input;
    
// }


// function importCsvToDb($filePath, $db)
// {
//     $table = 'patients';
//     $fileHandle = fopen($filePath, 'r');

//     fgetcsv($fileHandle); // saltare l'intestazione del CSV.


//     $query = "INSERT INTO $table (FirstName, LastName, BirthDate, Email, Symptoms, Diagnosis, Location, DiagnosisDate, PatientStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
//     $stmt = $db->prepare($query);

//     $counter = 0;


//     while (($rowData = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
//         $sanitizedRowData = array_map('sanitizeInput', $rowData);
   
//         $stmt->execute($sanitizedRowData);

//         $counter++;

//         echo "Dato numero {$counter} inserito con successo.\n";
//     }

//     fclose($fileHandle);
// }


//     if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['csv_file'])) {
//         $database = new Database();
//         $db = $database->getConnection();
    
//         $filePath = $_FILES['csv_file']['tmp_name'];
//         importCsvToDb($filePath, $db);
//         echo "Importazione completata con successo.";
//     } else {
//         echo "Errore nell'upload del file.";
//     }
  

