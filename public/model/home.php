<?php
class Home {
 
    // database connection and table name
    private $conn;
    private $table_name = "home";
 
    // object properties
    public $id;
    public $carousel_id;
    public $description;
    public $sub_title;    
    public $button;    
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;
 
    public function __construct($db){
        $this->conn = $db;
    }    

    // ACTIONS

    public function create($data) {
        $payload = [
            'carousel_id' => $data['carousel_id'] ?? null,
            'description' => $data['description'] ?? null,
            'sub_title' => $data['sub_title'] ?? null,
            'button' => $data['button'] ?? null,
            'created_user_id' => $data['created_user_id'] ?? null,
            'created_date' => $data['created_date'] ?? null,
            'updated_user_id' => $data['updated_user_id'] ?? null,
            'updated_date' => $data['updated_date'] ?? null
        ];

        $query = "INSERT INTO `" . $this->table_name . "`
            (carousel_id, description, sub_title, button, created_user_id, created_date,
            updated_user_id, updated_date)
            VALUES
            (:carousel_id, :description, :sub_title, :button, :created_user_id, :created_date,
            :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($payload);
    }

    public function update($data) {
        $home = [
            'id' => $data['id'],
            'carousel_id' => $data['carousel_id'] ?? null,
            'description' => $data['description'] ?? null,
            'sub_title' => $data['sub_title'] ?? null,
            'button' => $data['button'] ?? null,
            'updated_user_id' => $data['updated_user_id'] ?? null,
            'updated_date' => $data['updated_date'] ?? null
        ];

        $query = "UPDATE `" . $this->table_name . "` SET 
            carousel_id = :carousel_id,
            description = :description,
            sub_title = :sub_title,
            button = :button,
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($home);
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
                ho.id as id,
                ho.carousel_id as carousel_id,
                c.title as carousel_title,
                c.image_file as carousel_image_file,
                c.image_path as carousel_image_path,
                ho.description as description,
                ho.sub_title as sub_title,
                ho.button as button,
                ho.created_user_id as created_user_id, 
                ho.created_date as created_date, 
                crus.name as created_user_name,
                ho.updated_user_id as updated_user_id, 
                ho.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` ho 
                inner join carousel c on c.id = ho.carousel_id
                inner join user upus on ho.updated_user_id = upus.id 
                inner join user crus on ho.created_user_id = crus.id
            ORDER BY ho.id";

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
                ho.id as id,
                ho.carousel_id as carousel_id,
                ca.title as carousel_title,
                ca.image_file as carousel_image_file,
                ca.image_path as carousel_image_path,
                ho.description as description,
                ho.sub_title as sub_title,
                ho.button as button,
                ho.created_user_id as created_user_id, 
                ho.created_date as created_date, 
                crus.name as created_user_name,
                ho.updated_user_id as updated_user_id, 
                ho.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` ho 
                inner join `carousel` ca on ca.id = ho.carousel_id
                inner join `user` upus on ho.updated_user_id = upus.id 
                inner join `user` crus on ho.created_user_id = crus.id
            ORDER BY ho.id
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
                ho.id as id,
                ho.carousel_id as carousel_id,  
                c.title as carousel_title,
                c.image_file as carousel_image_file,
                c.image_path as carousel_image_path,
                ho.description as description,
                ho.sub_title as sub_title,
                ho.button as button,               
                ho.created_user_id as created_user_id, 
                ho.created_date as created_date, 
                crus.name as created_user_name,
                ho.updated_user_id as updated_user_id, 
                ho.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` ho 
                inner join carousel c on c.id = ho.carousel_id
                inner join user upus on ho.updated_user_id = upus.id 
                inner join user crus on ho.created_user_id = crus.id
            WHERE ho.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function search($search, $limit, $offset) {
        $query = "
            SELECT 
                ho.id as id,
                ho.carousel_id as carousel_id,
                ca.title as carousel_title,
                ca.image_file as carousel_image_file,
                ca.image_path as carousel_image_path,
                ho.description as description,
                ho.sub_title as sub_title,
                ho.button as button,               
                ho.created_user_id as created_user_id,  
                ho.created_date as created_date, 
                crus.name as created_user_name,
                ho.updated_user_id as updated_user_id, 
                ho.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` ho 
                inner join carousel ca on ca.id = ho.carousel_id
                inner join user upus on ho.updated_user_id = upus.id 
                inner join user crus on ho.created_user_id = crus.id
            WHERE
                LOWER(IFNULL(ho.description, '')) LIKE LOWER('%$search%')
                or LOWER(IFNULL(ho.sub_title, '')) LIKE LOWER('%$search%')
                or LOWER(IFNULL(ca.title, '')) LIKE LOWER('%$search%')
            ORDER BY ho.description
            LIMIT $limit OFFSET $offset";    

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $items = [];
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row;
            }
            
            return $items;
    }

    // VALIDATIONS

    public function existsByDescription($description) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE description = :description";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['description' => $description]);

        return $stmt->rowCount() > 0;
    }       

    public function existsByDescriptionWhenEdit($description, $id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE description = :description and id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'description' => $description,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    } 
}
?>