<?php
class MiniTitle {
 
    // database connection and table name
    private $conn;
    private $table_name = "mini_title";
 
    // object properties
    public $id;
    public $title_01;
    public $title_02;
    public $title_03;
    public $title_04;
    public $title_05;
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;
 
    public function __construct($db){
        $this->conn = $db;
    }

    // ACTIONS

    public function create($miniTitleData) {
        $data = [
            'title_01' => $miniTitleData['title_01'] ?? null,
            'title_02' => $miniTitleData['title_02'] ?? null,
            'title_03' => $miniTitleData['title_03'] ?? null,
            'title_04' => $miniTitleData['title_04'] ?? null,
            'title_05' => $miniTitleData['title_05'] ?? null,
            'created_user_id' => $miniTitleData['created_user_id'] ?? null,
            'created_date' => $miniTitleData['created_date'] ?? null,
            'updated_user_id' => $miniTitleData['updated_user_id'] ?? null,
            'updated_date' => $miniTitleData['updated_date'] ?? null
        ];

        $query = "INSERT INTO `" . $this->table_name . "`
            (title_01, title_02, title_03, title_04, title_05, created_date, created_user_id, updated_date, updated_user_id)
            VALUES
            (:title_01, :title_02, :title_03, :title_04, :title_05, :created_date, :created_user_id, :updated_date, :updated_user_id)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($data);
    }

    public function update($data) {
        $miniTitle = [
            'id' => $data['id'],
            'title_01' => $data['title_01'] ?? null,
            'title_02' => $data['title_02'] ?? null,
            'title_03' => $data['title_03'] ?? null,
            'title_04' => $data['title_04'] ?? null,
            'title_05' => $data['title_05'] ?? null,
            'updated_user_id' => $data['updated_user_id'] ?? null,
            'updated_date' => $data['updated_date'] ?? null
        ];

        $query = "UPDATE `" . $this->table_name . "` SET 
            title_01 = :title_01,
            title_02 = :title_02,
            title_03 = :title_03,
            title_04 = :title_04,
            title_05 = :title_05,
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($miniTitle);
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
                mt.id as id,
                mt.title_01 as title_01,
                mt.title_02 as title_02,
                mt.title_03 as title_03,
                mt.title_04 as title_04,
                mt.title_05 as title_05,
                mt.created_user_id as created_user_id, 
                mt.created_date as created_date, 
                crus.name as created_user_name,
                mt.updated_user_id as updated_user_id, 
                mt.updated_date as updated_date, 
                upus.name as updated_user_name
            FROM 
                `" . $this->table_name . "` mt
                inner join `user` upus on mt.updated_user_id = upus.id 
                inner join `user` crus on mt.created_user_id = crus.id
            ORDER BY mt.title_01";

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
                mt.id as id,
                mt.title_01 as title_01,
                mt.title_02 as title_02,
                mt.title_03 as title_03,
                mt.title_04 as title_04,
                mt.title_05 as title_05,
                mt.created_user_id as created_user_id, 
                mt.created_date as created_date, 
                crus.name as created_user_name,
                mt.updated_user_id as updated_user_id, 
                mt.updated_date as updated_date, 
                upus.name as updated_user_name
            FROM 
                `" . $this->table_name . "` mt
                inner join `user` upus on mt.updated_user_id = upus.id 
                inner join `user` crus on mt.created_user_id = crus.id
            WHERE mt.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function search($search) {
        $query = "
            SELECT 
                mt.id as id,
                mt.title_01 as title_01,
                mt.title_02 as title_02,
                mt.title_03 as title_03,
                mt.title_04 as title_04,
                mt.title_05 as title_05,
                mt.created_user_id as created_user_id, 
                mt.created_date as created_date, 
                crus.name as created_user_name,
                mt.updated_user_id as updated_user_id, 
                mt.updated_date as updated_date, 
                upus.name as updated_user_name
            FROM 
                `" . $this->table_name . "` mt
                inner join `user` upus on mt.updated_user_id = upus.id 
                inner join `user` crus on mt.created_user_id = crus.id
            WHERE 
                LOWER(mt.title_01) LIKE LOWER(:search) or 
                LOWER(mt.title_02) LIKE LOWER(:search) or 
                LOWER(mt.title_03) LIKE LOWER(:search) or 
                LOWER(mt.title_04) LIKE LOWER(:search) or 
                LOWER(mt.title_05) LIKE LOWER(:search)
            ORDER BY mt.title_01";   

        $stmt = $this->conn->prepare( $query );
        $stmt->execute(['search' => $search]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // VALIDATIONS

    public function existsByTitle($title) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE title_01 = :title";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['title' => $title]);
        
        return $stmt->rowCount() > 0;
    }    

    public function existsByTitleWhenEdit($title, $id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE (title_01 = :title) and (id <> :id)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'title' => $title,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    } 
}
?> 