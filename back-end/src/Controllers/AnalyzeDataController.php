<?php

namespace Driver\BackEnd\Controllers;

use Driver\BackEnd\DB\Database;
use Driver\BackEnd\Controllers\Controller;

class AnalyzeDataController extends Controller
{
    public function run()
    {
        $caseThreshold = isset($_GET['caseThreshold']) ? (int) $_GET['caseThreshold'] : 3; // Default
        $startDate = $_GET['startDate'] ?? '1900-01-01'; // Default 
        $endDate = $_GET['endDate'] ?? date('Y-m-d'); // Default 
        $epidemics = $this->detectEpidemics($caseThreshold, $startDate, $endDate);
        echo json_encode($epidemics);
    }

    private function detectEpidemics($caseThreshold, $startDate, $endDate)
    {
        $database = new Database();
        $db = $database->getConnection();

        // Modifica la query per includere le giuste tabelle e campi
        $query = "SELECT diagnoses.location, diseases.name AS disease_name, COUNT(*) as CaseCount
              FROM diagnoses
              JOIN diseases ON diagnoses.disease_id = diseases.id
              WHERE diagnoses.diagnosis_date BETWEEN :startDate AND :endDate
              GROUP BY diagnoses.location, diseases.name
              HAVING CaseCount >= :caseThreshold
              ORDER BY CaseCount DESC
              LIMIT 30
";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':caseThreshold', $caseThreshold, \PDO::PARAM_INT);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }
}


//TODO: add sanitize imput