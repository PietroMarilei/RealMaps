<?php
// percorso del file attuale
namespace Driver\BackEnd\Controllers;
// importazioni 
use Driver\BackEnd\DB\Database;
use Driver\BackEnd\Controllers\Controller;



class GetDataController extends Controller
{
    public function run()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $rowsPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

        $offset = ($page - 1) * $rowsPerPage;

        $database = new Database();
        $db = $database->getConnection();

        try {

            $totalStmt = $db->prepare("SELECT COUNT(*) AS total FROM diagnoses");
            $totalStmt->execute();
            $totalResult = $totalStmt->fetch(\PDO::FETCH_ASSOC);
            $totalRecords = $totalResult['total'];

            // Query con LIMIT e OFFSET per la paginazione, order by id 
            $stmt = $db->prepare("
            SELECT diagnoses.*, diseases.name AS disease_name
            FROM diagnoses
            JOIN diseases ON diagnoses.disease_id = diseases.id
            JOIN patients ON diagnoses.patient_id = patients.id
            ORDER BY diagnoses.id ASC
            LIMIT :limit OFFSET :offset
");

            $stmt->bindParam(':limit', $rowsPerPage, \PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);


            echo json_encode([
                'data' => $results,
                'totalRecords' => $totalRecords,
                'page' => $page,
                'rowsPerPage' => $rowsPerPage,
            ]);
        } catch (\PDOException $e) {
            echo "Errore: " . $e->getMessage();
        }
    }
}
