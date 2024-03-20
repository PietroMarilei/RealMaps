<?php

namespace Driver\BackEnd\Controllers;

use Driver\BackEnd\DB\Database;

class SearchDataController extends Controller
{
    public function run()
    {
        $criteria = array_map(array($this, 'sanitizeInput'), $_GET);
        $currentPage = isset($criteria['page']) ? intval($criteria['page']) : 1;
        $results = $this->searchData($criteria, $currentPage);
        $totalPages = $this->calculateTotalPages($this->getResultsCount($criteria), isset($criteria['per_page']) ? $criteria['per_page'] : 30);
        echo json_encode(['results' => $results, 'total_pages' => $totalPages]);
    }

    private function searchData($criteria, $currentPage = 1, $perPage = 30)
    {

        $database = new Database();
        $db = $database->getConnection();
        $offset = ($currentPage - 1) * $perPage;


        $query = "SELECT diagnoses.id AS diagnose_id, diagnoses.location, diseases.name AS disease_name, diagnoses.symptoms, diagnoses.diagnosis_date 
          FROM diagnoses
          INNER JOIN patients ON diagnoses.patient_id = patients.id
          INNER JOIN diseases ON diagnoses.disease_id = diseases.id
          WHERE 1=1";


        $params = [];

        if (!empty($criteria['symptoms'])) {
            $query .= " AND diagnoses.symptoms LIKE ?";
            $params[] = "%" . $criteria['symptoms'] . "%";
        }

        if (!empty($criteria['disease'])) {
            $query .= " AND diseases.name = ?";
            $params[] = $criteria['disease'];
        }

        if (!empty($criteria['location'])) {
            $query .= " AND diagnoses.location = ?";
            $params[] = $criteria['location'];
        }

        if (!empty($criteria['diagnosis_date_start']) && !empty($criteria['diagnosis_date_end'])) {
            $query .= " AND diagnoses.diagnosis_date BETWEEN ? AND ?";
            $params[] = $criteria['diagnosis_date_start'];
            $params[] = $criteria['diagnosis_date_end'];
        } elseif (!empty($criteria['diagnosis_date_start'])) {
            $query .= " AND diagnoses.diagnosis_date >= ?";
            $params[] = $criteria['diagnosis_date_start'];
        } elseif (!empty($criteria['diagnosis_date_end'])) {
            $query .= " AND diagnoses.diagnosis_date <= ?";
            $params[] = $criteria['diagnosis_date_end'];
        }
        $orderBy = isset($criteria['order_by']) ? $criteria['order_by'] : 'diagnoses.id';
        $order = isset($criteria['order']) && strtolower($criteria['order']) === 'desc' ? 'DESC' : 'ASC';

        $query .= " ORDER BY $orderBy $order"; 
        $query .= " LIMIT $perPage OFFSET $offset";
        
        $stmt = $db->prepare($query);
        foreach ($params as $index => $param) {
            $stmt->bindValue($index + 1, $param);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function sanitizeInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public function calculateTotalPages($resultsCount, $perPage = 30)
    {
        return ceil($resultsCount / $perPage);
    }
    private function getResultsCount($criteria)
    {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT COUNT(*) AS total_results
              FROM diagnoses
              INNER JOIN patients ON diagnoses.patient_id = patients.id
              INNER JOIN diseases ON diagnoses.disease_id = diseases.id
              WHERE 1=1";

        $params = [];

        if (!empty($criteria['symptoms'])) {
            $query .= " AND diagnoses.symptoms LIKE ?";
            $params[] = "%" . $criteria['symptoms'] . "%";
        }

        if (!empty($criteria['disease'])) {
            $query .= " AND diseases.name = ?";
            $params[] = $criteria['disease'];
        }

        if (!empty($criteria['location'])) {
            $query .= " AND diagnoses.location = ?";
            $params[] = $criteria['location'];
        }

        if (!empty($criteria['diagnosis_date_start']) && !empty($criteria['diagnosis_date_end'])) {
            $query .= " AND diagnoses.diagnosis_date BETWEEN ? AND ?";
            $params[] = $criteria['diagnosis_date_start'];
            $params[] = $criteria['diagnosis_date_end'];
        } elseif (!empty($criteria['diagnosis_date_start'])) {
            $query .= " AND diagnoses.diagnosis_date >= ?";
            $params[] = $criteria['diagnosis_date_start'];
        } elseif (!empty($criteria['diagnosis_date_end'])) {
            $query .= " AND diagnoses.diagnosis_date <= ?";
            $params[] = $criteria['diagnosis_date_end'];
        }

        $stmt = $db->prepare($query);
        foreach ($params as $index => $param) {
            $stmt->bindValue($index + 1, $param);
        }
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['total_results'];
    }

}
