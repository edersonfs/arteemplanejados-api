<?php
class AboutUs {
 
    // database connection and table name
    private $conn;
    private $table_name = "about_us";
 
    // object properties
    public $id;
    public $title;
    public $sub_title;
    public $content;
    public $video;
    public $image_file;
    public $image_path;
    public $image_file_02;
    public $image_path_02;
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
            (title, sub_title, content, video, image_file, image_path, 
            image_file_02, image_path_02,
            created_date, created_user_id, updated_date, updated_user_id)
            VALUES
            (:title, :sub_title, :content, :video, :image_file, :image_path, 
            :image_file_02, :image_path_02,
            :created_date, :created_user_id, :updated_date, :updated_user_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':sub_title', $data['sub_title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':video', $data['video']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':image_file_02', $data['image_file_02']);
        $stmt->bindParam(':image_path_02', $data['image_path_02']);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        

        return $stmt->execute($data);
    }

    public function update($data) {
        $query = "UPDATE `" . $this->table_name . "` SET 
            title = :title,
            sub_title = :sub_title,
            content = :content,
            video = :video,
            image_file = :image_file,
            image_path = :image_path,
            image_file_02 = :image_file_02,
            image_path_02 = :image_path_02,            
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':sub_title', $data['sub_title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':video', $data['video']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':image_file_02', $data['image_file_02']);
        $stmt->bindParam(':image_path_02', $data['image_path_02']);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
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
                au.id,
                au.title,
                au.sub_title,
                au.content,
                au.video,
                au.image_file,
                au.image_path,
                au.image_file_02,
                au.image_path_02,
                au.created_user_id as created_user_id, 
                au.created_date as created_date, 
                crus.name as created_user_name,
                au.updated_user_id as updated_user_id, 
                au.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` au
                inner join user upus on au.updated_user_id = upus.id 
                inner join user crus on au.created_user_id = crus.id
            ORDER BY au.title";

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
                au.id,
                au.title,
                au.sub_title,
                au.content,
                au.video,
                au.image_file,
                au.image_path,
                au.image_file_02,
                au.image_path_02,
                au.created_user_id as created_user_id, 
                au.created_date as created_date, 
                crus.name as created_user_name,
                au.updated_user_id as updated_user_id, 
                au.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` au
                inner join user upus on au.updated_user_id = upus.id 
                inner join user crus on au.created_user_id = crus.id
            WHERE au.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function search($search) {
        $query = "
            SELECT 
                au.id,
                au.title,
                au.sub_title,
                au.content,
                au.video,
                au.image_file,
                au.image_path,
                au.image_file_02,
                au.image_path_02,
                au.created_user_id as created_user_id, 
                au.created_date as created_date, 
                crus.name as created_user_name,
                au.updated_user_id as updated_user_id, 
                au.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` au
                inner join user upus on au.updated_user_id = upus.id 
                inner join user crus on au.created_user_id = crus.id
            WHERE 
                LOWER(au.title) LIKE LOWER(:search)
                or LOWER(au.sub_title) LIKE LOWER(:search)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['search' => $search]);
    
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
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE title = :title and id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'title' => $title,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }    
}
?> 