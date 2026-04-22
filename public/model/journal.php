<?php
class Journal {
 
    // database connection and table name
    private $conn;
    private $table_name = "journal";
 
    // object properties
    public $id;
    public $title;
    public $month;
    public $year;
    public $content;    
    public $image_file;
    public $image_path;    
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
            (title, month, year, content, image_file, image_path,
            created_date, created_user_id, updated_date, updated_user_id)
            VALUES
            (:title, :month, :year, :content, :image_file, :image_path,
            :created_date, :created_user_id, :updated_date, :updated_user_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':month', $data['month']);
        $stmt->bindParam(':year', $data['year']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }    

    public function update($data) {
        $query = "UPDATE `" . $this->table_name . "` SET 
            title = :title,
            month = :month,
            year = :year,
            content = :content,            
            image_file = :image_file,
            image_path = :image_path,                        
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':month', $data['month']);
        $stmt->bindParam(':year', $data['year']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM `" . $this->table_name . "` WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    // GET

    public function getAll() {
        $query = "
            SELECT 
                j.id,
                j.title,
                j.month,
                j.year,
                j.content,
                j.image_file,
                j.image_path,
                crus.name as created_user_name,
                j.updated_user_id as updated_user_id, 
                j.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` j
                INNER JOIN user upus ON j.updated_user_id = upus.id 
                INNER JOIN user crus ON j.created_user_id = crus.id
            ORDER BY j.title";

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
                j.id,
                j.title,
                j.month,
                j.year,
                j.content,
                j.image_file,
                j.image_path,
                crus.name as created_user_name,
                j.updated_user_id as updated_user_id, 
                j.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` j
                INNER JOIN user upus ON j.updated_user_id = upus.id 
                INNER JOIN user crus ON j.created_user_id = crus.id
            WHERE j.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search) {
        $query = "
            SELECT 
                j.id,
                j.title,
                j.month,
                j.year,
                j.content,
                j.image_file,
                j.image_path,
                crus.name as created_user_name,
                j.updated_user_id as updated_user_id, 
                j.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` j
                INNER JOIN user upus ON j.updated_user_id = upus.id 
                INNER JOIN user crus ON j.created_user_id = crus.id
            WHERE 
                LOWER(j.title) LIKE LOWER(:search)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['search' => "%" . $search . "%"]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // VALIDATIONS

    public function existsByTitle($title) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE title = :title";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['title' => $title]);

        return $stmt->rowCount() > 0;
    }

    public function existsByTitleWhenEdit($title, $id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE title = :title AND id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'title' => $title,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }  
}
?> 