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

        $query = "SELECT Location, Diagnosis, COUNT(Diagnosis) as Count 
                  FROM patients 
                  WHERE DiagnosisDate BETWEEN :startDate AND :endDate
                  GROUP BY Location, Diagnosis 
                  HAVING Count > :caseThreshold";

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