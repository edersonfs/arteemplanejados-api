<?php
class Home {
 
    // database connection and table name
    private $conn;
    private $table_name = "home";
 
    // object properties
    public $id;
    public $mini_title_id;
    public $title;
    public $sub_title;
    public $content;
    public $image_file;
    public $image_path;
    public $video;
    public $button;
    public $active;
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
            (mini_title_id, title, sub_title, content, image_file, image_path, 
            video, button, active, created_user_id, created_date, 
            updated_user_id, updated_date)
            VALUES
            (:mini_title_id, :title, :sub_title, :content, :image_file, :image_path, 
            :video, :button, :active, :created_user_id, :created_date, 
            :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':mini_title_id', $data['mini_title_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':sub_title', $data['sub_title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':video', $data['video']);
        $stmt->bindParam(':button', $data['button']);
        $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
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
            mini_title_id = :mini_title_id,
            title = :title,
            sub_title = :sub_title,
            content = :content,
            video = :video,
            button = :button,
            active = :active,
            image_file = :image_file,
            image_path = :image_path,
            updated_user_id = :updated_user_id,
            updated_date = :updated_date
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':mini_title_id', $data['mini_title_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':sub_title', $data['sub_title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':video', $data['video']);
        $stmt->bindParam(':button', $data['button']);
        $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
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
                ho.id as id,
                ho.mini_title_id as mini_title_id,
                mt.title_01 as title_01,
                mt.title_02 as title_02,
                mt.title_03 as title_03,
                mt.title_04 as title_04,
                mt.title_05 as title_05,
                ho.title as title,
                ho.sub_title as sub_title,
                ho.content as content,
                ho.image_file as image_file,
                ho.image_path as image_path,
                ho.video as video,
                ho.button as button,            
                ho.active as active,               
                ho.created_user_id as created_user_id, 
                ho.created_date as created_date, 
                crus.name as created_user_name,
                ho.updated_user_id as updated_user_id, 
                ho.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` ho 
                inner join mini_title mt on mt.id = ho.mini_title_id
                inner join user upus on ho.updated_user_id = upus.id 
                inner join user crus on ho.created_user_id = crus.id
            ORDER BY ho.title";    

        $stmt = $this->conn->prepare( $query );
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
                ho.mini_title_id as mini_title_id,
                mt.title_01 as title_01,
                mt.title_02 as title_02,
                mt.title_03 as title_03,
                mt.title_04 as title_04,
                mt.title_05 as title_05,
                ho.title as title,
                ho.sub_title as sub_title,
                ho.content as content,
                ho.image_file as image_file,
                ho.image_path as image_path,
                ho.video as video,
                ho.button as button,            
                ho.active as active,               
                ho.created_user_id as created_user_id, 
                ho.created_date as created_date, 
                crus.name as created_user_name,
                ho.updated_user_id as updated_user_id, 
                ho.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` ho 
                inner join mini_title mt on mt.id = ho.mini_title_id
                inner join user upus on ho.updated_user_id = upus.id 
                inner join user crus on ho.created_user_id = crus.id
            WHERE ho.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search) {
        $query = "
            SELECT 
                ho.id as id,
                ho.mini_title_id as mini_title_id,
                mt.title_01 as title_01,
                ho.title as title,
                ho.sub_title as sub_title,
                ho.content as content,
                ho.image_file as image_file,
                ho.image_path as image_path,
                ho.video as video,
                ho.button as button,            
                ho.active as active,               
                ho.created_user_id as created_user_id, 
                ho.created_date as created_date, 
                crus.name as created_user_name,
                ho.updated_user_id as updated_user_id, 
                ho.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` ho 
                inner join mini_title mt on mt.id = ho.mini_title_id
                inner join user upus on ho.updated_user_id = upus.id 
                inner join user crus on ho.created_user_id = crus.id
            WHERE
                LOWER(ho.title) LIKE LOWER(:search)
                or LOWER(mt.title_01) LIKE LOWER(:search)";    

        $stmt = $this->conn->prepare( $query );
        $stmt->execute(['search' => $search]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    
   
    // VALIDATIONS

    public function existsByTitle($title) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE title = :title";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['title' => $title]);

        return $stmt->rowCount() > 0;
    }

    public function existsByTitleWhenEdit($title, $id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE title = :title and id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'title' => $title,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }
}
?>