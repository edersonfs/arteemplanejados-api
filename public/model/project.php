<?php
class Project {

    // Database connection and table name
    private $conn;
    private $table_name = "project";

    // Object properties
    public $id;
    public $name;
    public $description;
    public $description_internal;
    public $start;
    public $active;
    public $contact;
    public $image_file;
    public $image_path;
    public $image_file_02;
    public $image_path_02;
    public $image_file_03;
    public $image_path_03;
    public $image_file_04;
    public $image_path_04;
    public $name_responsible;
    public $position;
    public $video;
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
            (name, description, description_internal, start, active, contact,
            image_file, image_path, image_file_02, image_path_02,
            image_file_03, image_path_03, image_file_04, image_path_04,
            name_responsible, position, video,
            created_date, created_user_id, updated_date, updated_user_id)
            VALUES 
            (:name, :description, :description_internal, :start, :active, :contact,
            :image_file, :image_path, :image_file_02, :image_path_02,
            :image_file_03, :image_path_03, :image_file_04, :image_path_04,
            :name_responsible, :position, :video,
            :created_date, :created_user_id, :updated_date, :updated_user_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':description_internal', $data['description_internal']);
        $stmt->bindParam(':start', $data['start']);
        $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
        $stmt->bindParam(':contact', $data['contact']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':image_file_02', $data['image_file_02']);
        $stmt->bindParam(':image_path_02', $data['image_path_02']);
        $stmt->bindParam(':image_file_03', $data['image_file_03']);
        $stmt->bindParam(':image_path_03', $data['image_path_03']);
        $stmt->bindParam(':image_file_04', $data['image_file_04']);
        $stmt->bindParam(':image_path_04', $data['image_path_04']);
        $stmt->bindParam(':name_responsible', $data['name_responsible']);
        $stmt->bindParam(':position', $data['position']);
        $stmt->bindParam(':video', $data['video']);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update($data) {
        $query = "UPDATE " . $this->table_name . " SET 
            name = :name,
            description = :description,
            description_internal = :description_internal,
            start = :start,
            active = :active,
            contact = :contact,
            image_file = :image_file,
            image_path = :image_path,
            image_file_02 = :image_file_02,
            image_path_02 = :image_path_02,
            image_file_03 = :image_file_03,
            image_path_03 = :image_path_03,
            image_file_04 = :image_file_04,
            image_path_04 = :image_path_04,
            name_responsible = :name_responsible,
            position = :position,
            video = :video,
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute($data);
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
                pr.id, 
                pr.name, 
                pr.description, 
                pr.description_internal, 
                pr.start, 
                pr.active, 
                pr.contact,
                pr.image_file,
                pr.image_path, 
                pr.image_file_02, 
                pr.image_path_02,
                pr.image_file_03, 
                pr.image_path_03, 
                pr.image_file_04, 
                pr.image_path_04,
                pr.name_responsible, 
                pr.position, 
                pr.video,
                pr.created_user_id as created_user_id, 
                pr.created_date as created_date, 
                crus.name as created_user_name,
                pr.updated_user_id as updated_user_id, 
                pr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` pr
                inner join user upus on pr.updated_user_id = upus.id 
                inner join user crus on pr.created_user_id = crus.id
            ORDER BY pr.name ASC";

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
                pr.id, 
                pr.name, 
                pr.description, 
                pr.description_internal, 
                pr.start, 
                pr.active, 
                pr.contact,
                pr.image_file,
                pr.image_path, 
                pr.image_file_02, 
                pr.image_path_02,
                pr.image_file_03, 
                pr.image_path_03, 
                pr.image_file_04, 
                pr.image_path_04,
                pr.name_responsible, 
                pr.position, 
                pr.video,
                pr.created_user_id as created_user_id, 
                pr.created_date as created_date, 
                crus.name as created_user_name,
                pr.updated_user_id as updated_user_id, 
                pr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` pr
                inner join user upus on pr.updated_user_id = upus.id 
                inner join user crus on pr.created_user_id = crus.id
            ORDER BY pr.created_date DESC
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
                pr.id, 
                pr.name, 
                pr.description, 
                pr.description_internal, 
                pr.start, 
                pr.active, 
                pr.contact,
                pr.image_file,
                pr.image_path, 
                pr.image_file_02, 
                pr.image_path_02,
                pr.image_file_03, 
                pr.image_path_03, 
                pr.image_file_04, 
                pr.image_path_04,
                pr.name_responsible, 
                pr.position, 
                pr.video,
                pr.created_user_id as created_user_id, 
                pr.created_date as created_date, 
                crus.name as created_user_name,
                pr.updated_user_id as updated_user_id, 
                pr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` pr
                inner join user upus on pr.updated_user_id = upus.id 
                inner join user crus on pr.created_user_id = crus.id
            WHERE pr.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function search($search) {
        $query = "
            SELECT 
                pr.id, 
                pr.name, 
                pr.description, 
                pr.description_internal, 
                pr.start, 
                pr.active, 
                pr.contact,
                pr.image_file,
                pr.image_path, 
                pr.image_file_02, 
                pr.image_path_02,
                pr.image_file_03, 
                pr.image_path_03, 
                pr.image_file_04, 
                pr.image_path_04,
                pr.name_responsible, 
                pr.position, 
                pr.video,
                pr.created_user_id as created_user_id, 
                pr.created_date as created_date, 
                crus.name as created_user_name,
                pr.updated_user_id as updated_user_id, 
                pr.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` pr
                inner join user upus on pr.updated_user_id = upus.id 
                inner join user crus on pr.created_user_id = crus.id
            WHERE pr.name LIKE LOWER(:search)
            or pr.description LIKE LOWER(:search)";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute(['search' => $search]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // VALIDATIONS
    
    public function existsByName($name) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name = :name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['name' => $name]);

        return $stmt->rowCount() > 0;
    }

    public function existsByNameWhenEdit($name, $id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name = :name and id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'name' => $name,
            'id' => $id
        ]);
        
        return $stmt->rowCount() > 0;
    }
    
}
?>
