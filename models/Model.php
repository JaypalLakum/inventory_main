<?php
require_once '../config/Database.php';

class Model {
    private $conn;
    private $table_name = "models";
    public $id;
    public $manufacturer_id;
    public $name;
    public $color;
    public $manufacturing_year;
    public $registration_number;
    public $note;
    public $image1;
    public $image2;
    public $is_sold;
    public $is_deleted;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create(): bool|string
    {
        try {
            $query = "INSERT INTO models (manufacturer_id,
                                          name, 
                                          color, 
                                          manufacturing_year, 
                                          registration_number, 
                                          note, 
                                          image1, 
                                          image2) 
                      VALUES (:manufacturer_id, 
                              :name, :color, 
                              :manufacturing_year, 
                              :registration_number, 
                              :note, 
                              :image1, 
                              :image2)";

            $stmt = $this->conn->prepare($query);

            // Sanitize inputs
            $this->manufacturer_id = htmlspecialchars(strip_tags($this->manufacturer_id));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->color = htmlspecialchars(strip_tags($this->color));
            $this->manufacturing_year = htmlspecialchars(strip_tags($this->manufacturing_year));
            $this->registration_number = htmlspecialchars(strip_tags($this->registration_number));
            $this->note = htmlspecialchars(strip_tags($this->note));
            $this->image1 = htmlspecialchars(strip_tags($this->image1));
            $this->image2 = htmlspecialchars(strip_tags($this->image2));

            // Bind parameters
            $stmt->bindParam(":manufacturer_id", $this->manufacturer_id);
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":color", $this->color);
            $stmt->bindParam(":manufacturing_year", $this->manufacturing_year);
            $stmt->bindParam(":registration_number", $this->registration_number);
            $stmt->bindParam(":note", $this->note);
            $stmt->bindParam(":image1", $this->image1);
            $stmt->bindParam(":image2", $this->image2);

            if($stmt->execute()) {
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "Registration number already exists";
            }
            return false;
        }
    }

    public function read(): array
    {
        $query = "SELECT models.*, manufacturers.name AS manufacturer_name
                  FROM models 
                  JOIN manufacturers 
                  ON models.manufacturer_id = manufacturers.id 
                  WHERE models.is_deleted = 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $models = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $models[] = [
                'id' => $row['id'],
                'manufacturer_id' => $row['manufacturer_id'],
                'manufacturer_name' => $row['manufacturer_name'],
                'name' => $row['name'],
                'color' => $row['color'],
                'manufacturing_year' => $row['manufacturing_year'],
                'registration_number' => $row['registration_number'],
                'note' => $row['note'],
                'image1' => $row['image1'],
                'image2' => $row['image2'],
                'is_sold' => $row['is_sold']
            ];
        }

        return $models;
    }

    public function getById() {
        $query = "SELECT m.*, mf.name as manufacturer_name 
                FROM " . $this->table_name . " m 
                JOIN manufacturers mf ON m.manufacturer_id = mf.id 
                WHERE m.id = :id AND m.is_deleted = 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function updateSoldStatus() {
        $query = "UPDATE " . $this->table_name . " 
                SET is_sold = :is_sold 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":is_sold", $this->is_sold);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "UPDATE " . $this->table_name . " 
                SET is_deleted = 1 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getInventoryCount() {
        $query = "SELECT manufacturer_id, COUNT(*) as available_count 
                FROM " . $this->table_name . " 
                WHERE is_deleted = 0 AND is_sold = 0 
                GROUP BY manufacturer_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?> 