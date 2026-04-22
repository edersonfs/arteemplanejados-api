<?php
class Accountability {
 
    // database connection and table name
    private $conn;
    private $table_name = "accountability";
 
    // object properties
    public $id;
    public $name;    
    public $date;   
    public $description;   
    public $entry;   
    public $exit;   
    public $balance;   
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;
 
    public function __construct($db){
        $this->conn = $db;
    }

    // ACTIONS

    public function create($accountabilityData) {
        $data = [
            'name' => $accountabilityData['name'] ?? null,
            'date' => $accountabilityData['date'] ?? null,
            'description' => $accountabilityData['description'] ?? null,
            'entry' => $accountabilityData['entry'] ?? null,
            'exit' => $accountabilityData['exit'] ?? null,
            'balance' => $accountabilityData['balance'] ?? null,
            'created_user_id' => $accountabilityData['created_user_id'] ?? null,
            'created_date' => $accountabilityData['created_date'] ?? null,
            'updated_user_id' => $accountabilityData['updated_user_id'] ?? null,
            'updated_date' => $accountabilityData['updated_date'] ?? null
        ];

        $query = "INSERT INTO `" . $this->table_name . "`
            (name, `date`, description, entry, `exit`, balance, created_date, created_user_id, updated_date, updated_user_id)
            VALUES
            (:name, :date, :description, :entry, :exit, :balance, :created_date, :created_user_id, :updated_date, :updated_user_id)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($data);
    }

    public function update($data) {
        $accountability = [
            'id' => $data['id'],
            'name' => $data['name'] ?? null,
            'date' => $data['date'] ?? null,
            'description' => $data['description'] ?? null,
            'entry' => $data['entry'] ?? null,
            'exit' => $data['exit'] ?? null,
            'balance' => $data['balance'] ?? null,
            'updated_user_id' => $data['updated_user_id'] ?? null,
            'updated_date' => $data['updated_date'] ?? null
        ];

        $query = "UPDATE `" . $this->table_name . "` SET 
            name = :name,  
            `date` = :date,
            description = :description,
            entry = :entry,
            `exit` = :exit,
            balance = :balance,          
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($accountability);
    }

    public function delete($id) {
        $query = "DELETE FROM `" . $this->table_name . "` WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Check how many rows were deleted
            return $stmt->rowCount() > 0;
        }

        return false;
    }

    // GET

    public function getAll() {
        $query = "
            SELECT 
                ac.id,
                ac.name, 
                ac.date, 
                ac.description, 
                ac.entry, 
                ac.exit, 
                ac.balance,
                ac.created_user_id as created_user_id, 
                ac.created_date as created_date, 
                crus.name as created_user_name,
                ac.updated_user_id as updated_user_id, 
                ac.updated_date as updated_date, 
                upus.name as updated_user_name
            FROM `" . $this->table_name . "` ac
                inner join `user` upus on ac.updated_user_id = upus.id 
                inner join `user` crus on ac.created_user_id = crus.id
            ORDER BY ac.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }
        return $items;
    }

    public function getPagination($limit, $offset) {
        $query = "
            SELECT 
                ac.id,
                ac.name, 
                ac.date, 
                ac.description, 
                ac.entry, 
                ac.exit, 
                ac.balance,
                ac.created_user_id as created_user_id, 
                ac.created_date as created_date, 
                crus.name as created_user_name,
                ac.updated_user_id as updated_user_id, 
                ac.updated_date as updated_date, 
                upus.name as updated_user_name
            FROM `" . $this->table_name . "` ac
                inner join `user` upus on ac.updated_user_id = upus.id 
                inner join `user` crus on ac.created_user_id = crus.id            
            ORDER BY ac.date DESC
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }
        return $items;
    }

    public function getById($id) {
        $query = "
            SELECT 
                ac.id,
                ac.name, 
                ac.date, 
                ac.description, 
                ac.entry, 
                ac.exit, 
                ac.balance,
                ac.created_user_id as created_user_id, 
                ac.created_date as created_date, 
                crus.name as created_user_name,
                ac.updated_user_id as updated_user_id, 
                ac.updated_date as updated_date, 
                upus.name as updated_user_name
            FROM `" . $this->table_name . "` ac
                inner join `user` upus on ac.updated_user_id = upus.id 
                inner join `user` crus on ac.created_user_id = crus.id
            WHERE ac.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function search($search) {
        $query = "
            SELECT 
                ac.id,
                ac.name, 
                ac.date, 
                ac.description, 
                ac.entry, 
                ac.exit, 
                ac.balance,
                ac.created_user_id as created_user_id, 
                ac.created_date as created_date, 
                crus.name as created_user_name,
                ac.updated_user_id as updated_user_id, 
                ac.updated_date as updated_date, 
                upus.name as updated_user_name
            FROM `" . $this->table_name . "` ac
                inner join `user` upus on ac.updated_user_id = upus.id 
                inner join `user` crus on ac.created_user_id = crus.id
            WHERE 
                LOWER(ac.name) LIKE LOWER(:search) or 
                LOWER(ac.description) LIKE LOWER(:search)
            ORDER BY ac.name ASC";   

        $stmt = $this->conn->prepare( $query );
        $stmt->execute(['search' => $search]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // VALIDATIONS    

    public function existsByName($name) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE name = :name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['name' => $name]);

        return $stmt->rowCount() > 0;
    }

    public function existsByNameWhenEdit($name, $id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE (name = :name) and (id <> :id)";

        $stmt = $this->conn->prepare($query);
       $stmt->execute([
            'name' => $name,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }    
}
?> 