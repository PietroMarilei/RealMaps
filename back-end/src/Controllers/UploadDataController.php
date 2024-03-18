<?php
// percorso del file attuale
namespace Driver\BackEnd\Controllers;
// importazioni
use Driver\BackEnd\DB\Database;
use Driver\BackEnd\Controllers\Controller;



class UploadDataController extends Controller
{
    private function sanitizeInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
    private function importCsvToDb($filePath, $db)
    {
        $fileHandle = fopen($filePath, 'r');
        fgetcsv($fileHandle); // Skip CSV header.

        $counter = 0;

        while (($rowData = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
            $sanitizedRowData = array_map([$this, 'sanitizeInput'], $rowData);

            // Insert patient data
            $patientQuery = "INSERT INTO patients (FirstName, LastName, BirthDate, Email, Status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($patientQuery);
            $stmt->execute(
                [$sanitizedRowData[0], //name
                $sanitizedRowData[1],  // name 2
                $sanitizedRowData[2], // birthdate
                $sanitizedRowData[3], //Email
                $sanitizedRowData[8]]); //stato
            $patientId = $db->lastInsertId();

            // Check if the disease exists and insert it if it doesn't
            $diseaseQuery = "INSERT INTO diseases (name) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
            $stmt = $db->prepare($diseaseQuery);
            $stmt->execute([$sanitizedRowData[5]]); // // diagnosi
            $diseaseId = $db->lastInsertId();

            // Insert diagnosis data
            $diagnosisQuery = "INSERT INTO diagnoses (patient_id, disease_id, diagnosis_date, location, symptoms) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($diagnosisQuery);

            //     var_dump('test here');
            // var_dump([
            //     $sanitizedRowData[0], //name
            //     $sanitizedRowData[1],  // name 2
            //     $sanitizedRowData[2], // birthdate
            //     $sanitizedRowData[3], //Email
            //     $sanitizedRowData[4], //sintomi
            //     $sanitizedRowData[5], // diagnosi
            //     $sanitizedRowData[6], //localita
            //     $sanitizedRowData[7],  //data diagnosi
            //     $sanitizedRowData[8], //stato
            //     $sanitizedRowData[9]
            // ]);
            // // die();
            $stmt->execute(
                [$patientId, 
                $diseaseId, 
                $sanitizedRowData[7], // Data di diagnosi
                $sanitizedRowData[6], // localitá diagnosi
                $sanitizedRowData[4]]); //sintomi

            $counter++;
        }

        fclose($fileHandle);

        return $counter; 
    }

    public function run()
    {
        $response = ['success' => false, 'message' => 'Errore nell\'upload del file.'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_FILES['csv_file'])) {
                $database = new Database();
                $db = $database->getConnection();
                $filePath = $_FILES['csv_file']['tmp_name'];
                try {
                    $db->beginTransaction();
                    $count = $this->importCsvToDb($filePath, $db);
                    $db->commit();
                    $response = ['success' => true, 'message' => "Importazione completata con successo. $count record inseriti."];
                } catch (\PDOException $e) {
                    $db->rollBack();
                    $response = ['success' => false, 'message' => "Errore del database: " . $e->getMessage()];
                }
            }
        } else {
            $response = ['success' => false, 'message' => 'Metodo non supportato.'];
        }

        echo json_encode($response);

        // //TODO: fare cartella helpers
        // function sanitizeInput($input)
        // {
        //     $input = str_replace(',', '-', $input);
        //     $input = preg_replace("/[^a-zA-Z0-9\s\.\-\_]/", "", $input);
        //     $input = preg_replace('/\s+/', ' ', $input);
        //     return $input;
        // }
        


        // function importCsvToDb($filePath, $db)
        // {
        //     $table = 'patients';
        //     $fileHandle = fopen($filePath, 'r');
        //     fgetcsv($fileHandle); // Saltare l'intestazione del CSV.
        //     $query = "INSERT INTO $table (FirstName, LastName, BirthDate, Email, Symptoms, Diagnosis, Location, DiagnosisDate, PatientStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //     $stmt = $db->prepare($query);
        //     // $counter = 0;
        //     while (($rowData = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
        //         $sanitizedRowData = array_map(function ($input) {
        //             $input = str_replace(',', '-', $input);
        //             $input = preg_replace("/[^a-zA-Z0-9\s\.\-\_]/", "", $input);
        //             $input = preg_replace('/\s+/', ' ', $input);
        //             return $input;
        //         }, $rowData);
        //         $stmt->execute($sanitizedRowData);
        //         // $counter++;
        //     }
        //     fclose($fileHandle);
        //     // return $counter;
        //     return; // Restituire il conteggio dei record inseriti
        // }

        // $response = ['success' => false, 'message' => 'Errore nell\'upload del file.'];

        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     if (!empty($_FILES['csv_file'])) {
        //         $database = new Database();
        //         $db = $database->getConnection();
        //         $filePath = $_FILES['csv_file']['tmp_name'];
        //         $count = importCsvToDb($filePath, $db);
        //         $response = ['success' => true, 'message' => "Importazione completata con successo. $count record inseriti."];
        //     }
        // } else {
        //     $response = ['success' => false, 'message' => 'Metodo non supportato.'];
        // }

        // echo json_encode($response);

    }
}
