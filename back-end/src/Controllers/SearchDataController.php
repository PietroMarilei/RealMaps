<?php

namespace Driver\BackEnd\Controllers;

use Driver\BackEnd\DB\Database;

class SearchDataController extends Controller
{
    public function run()
    {
        //prevent INjection sql
        $criteria = array_map(array($this, 'sanitizeInput'), $_GET);
        $results = $this->searchData($criteria);
        echo json_encode($results);
    }

    private function SearchData($criteria)
    {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT * FROM patients WHERE TRUE"; // Imposta una condizione sempre vera come punto di partenza
        $params = [];

        foreach ($criteria as $key => $value) {
            if (!empty($value)) {
                $query .= " AND $key LIKE ?";
                $params[] = "%" . $value . "%";
            }
        }

        // parte la query
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
