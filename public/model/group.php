<?php
class Group {
 
    // database connection and table name
    private $conn;
    private $table_name = "group";
 
    // object properties
    public $id;
    public $name;    
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;
 
    public function __construct($db){
        $this->conn = $db;
    }

    // ACTIONS

    public function create($groupData) {
        $data = [
            'name' => $groupData['name'] ?? null,        
            'created_user_id' => $groupData['created_user_id'] ?? null,
            'created_date' => $groupData['created_date'] ?? null,
            'updated_user_id' => $groupData['updated_user_id'] ?? null,
            'updated_date' => $groupData['updated_date'] ?? null
        ];

        $query = "INSERT INTO `" . $this->table_name . "`
            (name, created_date, created_user_id, updated_date, updated_user_id)
            VALUES
            (:name, :created_date, :created_user_id, :updated_date, :updated_user_id)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($data);
    }

    public function update($data) {
        $group = [
            'id' => $data['id'],
            'name' => $data['name'] ?? null,        
            'updated_user_id' => $data['updated_user_id'] ?? null,
            'updated_date' => $data['updated_date'] ?? null
        ];

        $query = "UPDATE `" . $this->table_name . "` SET 
            name = :name,            
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($group);
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

    public function getAll($group_id) {
        $query = "
            SELECT 
                gr.id as id,
                gr.name as name,            
                gr.updated_user_id as updated_user_id, 
                gr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` gr 
                inner join `user` upus on gr.updated_user_id = upus.id 
                inner join `user` crus on gr.created_user_id = crus.id
            WHERE (gr.id <> 1 or :group_id = 1)
            ORDER BY gr.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['group_id' => $group_id]);
        
        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function getPagination($limit, $offset, $group_id) {
        $query = "
             SELECT 
                gr.id as id,
                gr.name as name,            
                gr.updated_user_id as updated_user_id, 
                gr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` gr 
                inner join `user` upus on gr.updated_user_id = upus.id 
                inner join `user` crus on gr.created_user_id = crus.id
            WHERE (gr.id <> 1 or :group_id = 1)
            ORDER BY gr.name
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['group_id' => $group_id]);
        
        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }
        
        return $items;
    }

    public function getById($id) {
        $query = "
            SELECT 
                gr.id as id,
                gr.name as name,                       
                gr.created_user_id as created_user_id, 
                gr.created_date as created_date, 
                crus.name as created_user_name,
                gr.updated_user_id as updated_user_id, 
                gr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` gr 
                inner join `user` upus on gr.updated_user_id = upus.id 
                inner join `user` crus on gr.created_user_id = crus.id
            WHERE gr.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function search($search, $limit, $offset, $group_id) {
        $query = "
            SELECT 
                gr.id as id,
                gr.name as name,            
                gr.updated_user_id as updated_user_id, 
                gr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` gr 
                inner join `user` upus on gr.updated_user_id = upus.id 
                inner join `user` crus on gr.created_user_id = crus.id
            WHERE 
                LOWER(gr.name) LIKE LOWER('%$search%') and (gr.id <> 1 or :group_id = 1)
            ORDER BY gr.name
            LIMIT $limit OFFSET $offset";    

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['group_id' => $group_id]);
            
            $items = [];
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row;
            }
            
            return $items;
    }

    // VALIDATIONS

    public function existsByName($name) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE name = :name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['name' => $name]);

        return $stmt->rowCount() > 0;
    }       

    public function existsByNameWhenEdit($name, $id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE name = :name and id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'name' => $name,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    } 
}
?> 