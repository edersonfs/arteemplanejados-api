<?php
class Collaborator {
 
    // database connection and table name
    private $conn;
    private $table_name = "collaborator";
 
    // object properties
    public $id;
    public $name;
    public $position;
    public $description;
    public $image_file;
    public $image_path;    
    public $order;  
    public $facebook;  
    public $instagram;  
    public $linkedin;  
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
            (name, position, description, image_file, image_path, 
            `order`, facebook, instagram, linkedin,
            created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:name, :position, :description, :image_file, :image_path, 
            :order, :facebook, :instagram, :linkedin,
            :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);
        $stmt->bindParam(':facebook', $data['facebook']);
        $stmt->bindParam(':instagram', $data['instagram']);
        $stmt->bindParam(':linkedin', $data['linkedin']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);

        return $stmt->execute();
    }

    public function update($data) {
        $query = "UPDATE `" . $this->table_name . "` SET 
            name = :name,
            position = :position,
            description = :description,
            image_file = :image_file,
            image_path = :image_path,
            `order` = :order,
            facebook = :facebook,
            instagram = :instagram,
            linkedin = :linkedin,
            updated_user_id = :updated_user_id,
            updated_date = :updated_date
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);
        $stmt->bindParam(':facebook', $data['facebook']);
        $stmt->bindParam(':instagram', $data['instagram']);
        $stmt->bindParam(':linkedin', $data['linkedin']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT); // 👈 important for WHERE

        return $stmt->execute();
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
                co.id,
                co.name,
                co.position,
                co.description,
                co.image_file,
                co.image_path,
                co.order,
                co.facebook,
                co.instagram,
                co.linkedin,
                co.created_user_id as created_user_id, 
                co.created_date as created_date, 
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id, 
                co.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` co
                inner join user upus on co.updated_user_id = upus.id 
                inner join user crus on co.created_user_id = crus.id
            ORDER BY co.name";

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
                co.id,
                co.name,
                co.position,
                co.description,
                co.image_file,
                co.image_path,
                co.order,
                co.facebook,
                co.instagram,
                co.linkedin,
                co.created_user_id as created_user_id, 
                co.created_date as created_date, 
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id, 
                co.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` co
                inner join user upus on co.updated_user_id = upus.id 
                inner join user crus on co.created_user_id = crus.id
            ORDER BY co.order
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
                co.id,
                co.name,
                co.position,
                co.description,
                co.image_file,
                co.image_path,
                co.order,
                co.facebook,
                co.instagram,
                co.linkedin,            
                co.created_user_id as created_user_id, 
                co.created_date as created_date, 
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id, 
                co.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` co
                inner join user upus on co.updated_user_id = upus.id 
                inner join user crus on co.created_user_id = crus.id
            WHERE co.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search) {
        $query = "
            SELECT 
                co.id,
                co.name,
                co.position,
                co.description,
                co.image_file,
                co.image_path,
                co.order,
                co.facebook,
                co.instagram,
                co.linkedin,
                co.created_user_id as created_user_id, 
                co.created_date as created_date, 
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id, 
                co.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` co
                inner join user upus on co.updated_user_id = upus.id 
                inner join user crus on co.created_user_id = crus.id
            WHERE 
                LOWER(co.name) LIKE LOWER(:search)
                or LOWER(co.position) LIKE LOWER(:search)";

        $stmt = $this->conn->prepare($query);
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