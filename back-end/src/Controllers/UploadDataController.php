<?php
// percorso del file attuale
namespace Driver\BackEnd\Controllers;
// importazioni
use Driver\BackEnd\DB\Database;
use Driver\BackEnd\Controllers\Controller;



class UploadDataController extends Controller
{
    public function run()
    {

        //TODO: fare cartella helpers
        function sanitizeInput($input)
        {
            $input = str_replace(',', '-', $input);
            $input = preg_replace("/[^a-zA-Z0-9\s\.\-\_]/", "", $input);
            $input = preg_replace('/\s+/', ' ', $input);
            return $input;
        }

        function importCsvToDb($filePath, $db)
        {
            $table = 'patients';
            $fileHandle = fopen($filePath, 'r');
            fgetcsv($fileHandle); // Saltare l'intestazione del CSV.
            $query = "INSERT INTO $table (FirstName, LastName, BirthDate, Email, Symptoms, Diagnosis, Location, DiagnosisDate, PatientStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            // $counter = 0;
            while (($rowData = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
                $sanitizedRowData = array_map(function ($input) {
                    $input = str_replace(',', '-', $input);
                    $input = preg_replace("/[^a-zA-Z0-9\s\.\-\_]/", "", $input);
                    $input = preg_replace('/\s+/', ' ', $input);
                    return $input;
                }, $rowData);
                $stmt->execute($sanitizedRowData);
                // $counter++;
            }
            fclose($fileHandle);
            // return $counter;
            return; // Restituire il conteggio dei record inseriti
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

    }
}
