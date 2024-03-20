<?php

namespace Driver\BackEnd\Controllers;

use Driver\BackEnd\DB\Database;

class SearchDataController extends Controller
{
    public function run()
    {
        // Prevent SQL Injection
        $criteria = array_map(array($this, 'sanitizeInput'), $_GET);
        $pagination = $this->searchData($criteria);
        echo json_encode($pagination);
    }

    private function searchData($criteria)
    {
        $database = new Database();
        $db = $database->getConnection();

        // Pagination settings
        $resultsPerPage = 10;
        $page = isset($criteria['page']) ? (int)$criteria['page'] : 1;
        $offset = ($page - 1) * $resultsPerPage;

        // Start building the query
        $query = "SELECT diagnoses.location, diseases.name AS disease_name, diagnoses.symptoms, diagnoses.diagnosis_date 
                  FROM diagnoses
                  INNER JOIN patients ON diagnoses.patient_id = patients.id
                  INNER JOIN diseases ON diagnoses.disease_id = diseases.id
                  WHERE 1=1";

        // Initialize parameters array
        $params = [];

        if (!empty($criteria['symptoms'])) {
            $query .= " AND diagnoses.symptoms LIKE ?";
            $params[] = "%" . $criteria['symptoms'] . "%";
        }

        if (!empty($criteria['disease'])) {
            $query .= " AND diseases.name = ?";
            $params[] =  $criteria['disease'];
        }

        if (!empty($criteria['location'])) {
            $query .= " AND diagnoses.location = ?";
            $params[] = $criteria['location'];
        }

        // Gestione dell'intervallo di diagnosis_date
        if (!empty($criteria['diagnosis_date_start']) && !empty($criteria['diagnosis_date_end'])) {
            $query .= " AND diagnoses.diagnosis_date BETWEEN ? AND ?";
            $params[] = $criteria['diagnosis_date_start'];
            $params[] = $criteria['diagnosis_date_end'];
        } else if (!empty($criteria['diagnosis_date_start'])) {
            // Solo data di inizio
            $query .= " AND diagnoses.diagnosis_date >= ?";
            $params[] = $criteria['diagnosis_date_start'];
        } else if (!empty($criteria['diagnosis_date_end'])) {
            // Solo data di fine
            $query .= " AND diagnoses.diagnosis_date <= ?";
            $params[] = $criteria['diagnosis_date_end'];
        }
        // pagination
        $query .= " ORDER BY diagnoses.diagnosis_date DESC LIMIT ? OFFSET ?";
        // Execute query
        $stmt = $db->prepare($query);
        $paramIndex = 1;
     
        // Bind filter parameters
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }

        // Bind LIMIT and OFFSET parameters (must be integers)
        $stmt->bindValue($paramIndex++, $resultsPerPage, \PDO::PARAM_INT);
        $stmt->bindValue($paramIndex, $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // calcola quante sono le pagine in tutto
        $countQuery = "SELECT COUNT(*) AS total FROM diagnoses
                       INNER JOIN patients ON diagnoses.patient_id = patients.id
                       INNER JOIN diseases ON diagnoses.disease_id = diseases.id
                       WHERE 1=1";

        // Reusing the same filter parameters
        $countStmt = $db->prepare($countQuery);
        $paramIndex = 1;

        foreach ($params as $param) {
            $countStmt->bindValue($paramIndex++, $param);
        }

        $countStmt->execute();
        $totalCount = $countStmt->fetch(\PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalCount / $resultsPerPage);

        // Return data with pagination info
        return [
            'results' => $results,
            'totalPages' => $totalPages
        ];
    }

    private function sanitizeInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}
