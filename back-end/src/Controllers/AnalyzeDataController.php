<?php

namespace Driver\BackEnd\Controllers;

use Driver\BackEnd\DB\Database;
use Driver\BackEnd\Controllers\Controller;

class AnalyzeDataController extends Controller
{
    public function run()
    {
        $caseThreshold = isset($_GET['caseThreshold']) ? (int) $_GET['caseThreshold'] : 5; 
        // default 5 casi
        $epidemics = $this->detectEpidemics($caseThreshold);
        echo json_encode($epidemics);
    }

    private function detectEpidemics($caseThreshold)
    {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT Location, Diagnosis, COUNT(Diagnosis) as Count 
                  FROM patients 
                  GROUP BY Location, Diagnosis 
                  HAVING Count > :caseThreshold";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':caseThreshold', $caseThreshold, \PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }
}

//TODO: add sanitize imput