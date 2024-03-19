<?php

namespace Driver\BackEnd\Controllers;

use Driver\BackEnd\DB\Database;

class SearchDataController extends Controller
{
    public function run()
    {
        // Prevent SQL Injection
        $criteria = array_map(array($this, 'sanitizeInput'), $_GET);
        $results = $this->searchData($criteria);
        echo json_encode($results);
    }

    private function searchData($criteria)
    {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT diagnoses.location, diseases.name AS disease_name, diagnoses.symptoms, diagnoses.diagnosis_date FROM diagnoses
                  JOIN patients ON diagnoses.patient_id = patients.id
                  JOIN diseases ON diagnoses.disease_id = diseases.id
                  WHERE 1=1 ";

        $params = [];

        if (!empty($criteria['BirthDate'])) {
            $query .= "AND patients.BirthDate = ?";
            $params[] = $criteria['BirthDate'];
        }

        if (!empty($criteria['Symptoms'])) {
            $query .= "AND diagnoses.symptoms LIKE ?";
            $params[] = "%" . $criteria['Symptoms'] . "%";
        }

        if (!empty($criteria['disease'])) {
            $query .= "AND diseases.name LIKE ?";
            $params[] = "%" . $criteria['disease'] . "%";
        }

        if (!empty($criteria['Location'])) {
            $query .= "AND diagnoses.location = ?";
            $params[] = $criteria['Location'];
        }

        if (!empty($criteria['diagnosis_date'])) {
            $query .= "AND diagnoses.diagnosis_date = ?";
            $params[] = $criteria['diagnosis_date'];
        }

        // Execute query
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function sanitizeInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}
