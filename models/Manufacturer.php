<?php
require_once '../config/Database.php';

class Manufacturer {
    private $conn;
    public $id;
    public $name;
    public $is_deleted;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create(): bool
    {
        // First check if name already exists
        if($this->nameExists()) {
            return false;
        }

        $query = "INSERT INTO manufacturers (name)
                  VALUES (:name)";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(":name", $this->name);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read(): array
    {
        $query = "SELECT id, name FROM manufacturers 
                  WHERE is_deleted = 0";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $manufacturers = [];
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $manufacturers[] = [
                'id' => $row['id'],
                'name' => $row['name']
            ];
        }

        return $manufacturers;
    }

    public function delete(): bool
    {
        $query = "UPDATE manufacturers 
                  SET is_deleted = 1 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function nameExists(): bool
    {
        $query = "SELECT id FROM manufacturers 
                  WHERE name = :name 
                  AND is_deleted = 0";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(":name", $this->name);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function hasModels(): bool
    {
        $query = "SELECT COUNT(*) as count 
                  FROM models 
                  WHERE manufacturer_id = :id 
                  AND is_deleted = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
