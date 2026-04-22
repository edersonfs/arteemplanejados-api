<?php
class Space {
 
    // database connection and table name
    private $conn;
    private $table_name = "space";
 
    // object properties
    public $id;    
    public $name;    
    public $tenant;
    public $description;
    public $price;
    public $start_date;
    public $start_time;
    public $end_date;
    public $end_time;        
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;
 
    public function __construct($db){
        $this->conn = $db;
    }

    // ACTIONS

    public function create($data) {
        $query = "INSERT INTO `" . $this->table_name . "` 
            (name, tenant, description, price, start_date, start_time, end_date, end_time,            
            created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:name, :tenant, :description, :price, :start_date, :start_time, :end_date, :end_time,            
            :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        // main fields
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':tenant', $data['tenant']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);

        return $stmt->execute();
    }

    public function update($data) {
        $space = [
            'id' => $data['id'],
            'name' => $data['name'] ?? null,      
            'tenant' => $data['tenant'] ?? null,  
            'description' => $data['description'] ?? null,  
            'price' => $data['price'] ?? null,  
            'start_date' => $data['start_date'] ?? null,  
            'start_time' => $data['start_time'] ?? null,  
            'end_date' => $data['end_date'] ?? null,  
            'end_time' => $data['end_time'] ?? null,  
            'updated_user_id' => $data['updated_user_id'] ?? null,
            'updated_date' => $data['updated_date'] ?? null
        ];

        $query = "UPDATE `" . $this->table_name . "` SET 
          name = :name,
          tenant = :tenant,
          description = :description,
          price = :price,
          start_date = :start_date,
          start_time = :start_time,
          end_date = :end_date,
          end_time = :end_time,          
          updated_user_id = :updated_user_id,
          updated_date = :updated_date
          WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($space);
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
                sp.id,
                sp.name,
                sp.tenant,
                sp.description,
                sp.price,
                sp.start_date,
                sp.start_time,
                sp.end_date,
                sp.end_time,                
                sp.created_user_id as created_user_id, 
                sp.created_date as created_date, 
                crus.name as created_user_name,
                sp.updated_user_id as updated_user_id, 
                sp.updated_date as updated_date, 
                upus.name as updated_user_name              
            FROM 
                `" . $this->table_name . "` sp
                inner join user upus on sp.updated_user_id = upus.id 
                inner join user crus on sp.created_user_id = crus.id
            ORDER BY sp.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }
        
        return $items;
    }

    public function getAllToCalendar() {
        $query = "
            SELECT                 
                sp.name,
                sp.tenant,
                sp.description,
                sp.price,
                sp.start_date,
                sp.start_time,
                sp.end_date,
                sp.end_time                             
            FROM 
                `" . $this->table_name . "` sp
            UNION ALL 
            SELECT                 
                ev.name,
                'AMCATD',
                ev.description,
                ev.price,
                ev.start_date,
                ev.start_time,
                ev.end_date,
                ev.end_time                             
            FROM 
                `event` ev";

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
                sp.id,
                sp.name,
                sp.tenant,
                sp.description,
                sp.price,
                sp.start_date,
                sp.start_time,
                sp.end_date,
                sp.end_time,
                sp.created_user_id as created_user_id, 
                sp.created_date as created_date, 
                crus.name as created_user_name,
                sp.updated_user_id as updated_user_id, 
                sp.updated_date as updated_date, 
                upus.name as updated_user_name              
            FROM 
                `" . $this->table_name . "` sp
                inner join user upus on sp.updated_user_id = upus.id 
                inner join user crus on sp.created_user_id = crus.id
            WHERE sp.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    

    public function search($search) {
        $query = "
            SELECT 
                sp.id,
                sp.name,
                sp.tenant,
                sp.description,
                sp.price,
                sp.start_date,
                sp.start_time,
                sp.end_date,
                sp.end_time,   
                sp.created_user_id as created_user_id, 
                sp.created_date as created_date, 
                crus.name as created_user_name,
                sp.updated_user_id as updated_user_id, 
                sp.updated_date as updated_date, 
                upus.name as updated_user_name              
            FROM 
                `" . $this->table_name . "` sp
                inner join user upus on sp.updated_user_id = upus.id 
                inner join user crus on sp.created_user_id = crus.id
            WHERE sp.name LIKE LOWER(:search)";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute(['search' => $search]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // VALIDATIONS
        
    public function existsByStartDate($startDate) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE start_date = :startDate";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['startDate' => $startDate]);

        return $stmt->rowCount() > 0;
    }

    public function existsByStartDateWhenEdit($startDate, $id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE start_date = :startDate and id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'startDate' => $startDate,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }
}
?> 