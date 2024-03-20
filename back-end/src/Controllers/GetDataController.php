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
        $rowsPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 30;
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
           //var_dump($sortColumn);
        
        $sortDirection = isset($_GET['direction']) && in_array($_GET['direction'], ['ASC', 'DESC']) ? $_GET['direction'] : 'ASC';

        $offset = ($page - 1) * $rowsPerPage;

        $database = new Database();
        $db = $database->getConnection();

        try {
            $totalStmt = $db->prepare("SELECT COUNT(*) AS total FROM diagnoses");
            $totalStmt->execute();
            $totalResult = $totalStmt->fetch(\PDO::FETCH_ASSOC);
            $totalRecords = $totalResult['total'];

            // BUG fix: why not ordering by disease->name? non esiste diagnones.name, ma diseases.name
            if ($sortColumn == "diseases.name" ) {
                $stmt = $db->prepare("
                SELECT diagnoses.*, diseases.name AS disease_name
                FROM diagnoses
                JOIN diseases ON diagnoses.disease_id = diseases.id
                ORDER BY $sortColumn $sortDirection
                LIMIT :limit OFFSET :offset
            ");  
            } else {
                $allowedSortColumns = ['id', 'diseases.name', 'symptoms', 'location', 'diagnosis_date', 'patient_id'];
                    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'id';
        
                    // Query con LIMIT e OFFSET per la paginazione, e dynamic order by
                    $stmt = $db->prepare("
                        SELECT diagnoses.*, diseases.name AS disease_name
                        FROM diagnoses
                        JOIN diseases ON diagnoses.disease_id = diseases.id
                        ORDER BY diagnoses.$sortColumn $sortDirection
                        LIMIT :limit OFFSET :offset
                    ");

            }

            $stmt->bindParam(':limit', $rowsPerPage, \PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'data' => $results,
                'totalRecords' => $totalRecords,
                'page' => $page,
                'rowsPerPage' => $rowsPerPage,
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
            ]);
        } catch (\PDOException $e) {
            echo "Errore: " . $e->getMessage();
        }
    }
    

}
