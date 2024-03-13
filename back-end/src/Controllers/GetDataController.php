<?php
// percorso del file attuale
namespace Driver\BackEnd\Controllers;
// importazioni 
use Driver\BackEnd\DB\Database;
use Driver\BackEnd\Controllers\Controller;



class GetDataController extends Controller {
    public function run() {

        $database = new Database();
        $db = $database->getConnection();
        
        try {
            $stmt = $db->prepare("SELECT * FROM patients");
            $stmt->execute();
        
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
            echo json_encode($results);
        } catch (\PDOException $e) {
            echo "Errore: " . $e->getMessage();
        }

    }
}
