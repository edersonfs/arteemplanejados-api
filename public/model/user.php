<?php
class User {
 
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    public $id;
    public $group_id;
    public $name;
    public $email;
    public $password;
    public $active;
    public $image_file;
    public $image_path;
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;
 
    public function __construct($db){
        $this->conn = $db;
    }

    //ACTIONS

    public function create($data) {       
        $query = "INSERT INTO `" . $this->table_name . "`
            (group_id, name, email, password, active, image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:group_id, :name, :email, :password, :active, :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':group_id', $data['group_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT); // 👈 force integer (0 or 1)
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);

        return $stmt->execute();
    }

    public function update($data) {       
        $query = "UPDATE `" . $this->table_name . "`
                SET group_id = :group_id,
                    name = :name,
                    email = :email,                    
                    active = :active,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':group_id', $data['group_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT); // 👈 bind ID for WHERE

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

    //GET

    public function getAll() {    
        $query = "
            SELECT 
                us.id as id, 
                gr.id as group_id,
                gr.name as group_name,
                us.name as name, 
                us.email as email, 
                us.active as active, 
                us.image_file as image_file,
                us.image_path as image_path,
                us.created_user_id as created_user_id, 
                us.created_date as created_date, 
                crus.name as created_user_name,
                us.updated_user_id as updated_user_id, 
                us.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` us 
                inner join `group` gr on us.group_id = gr.id
                inner join `user` upus on us.updated_user_id = upus.id 
                inner join `user` crus on us.created_user_id = crus.id
            ORDER BY us.name";  

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        $users = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }

        return $users;
    }

    public function getPagination($limit, $offset) {
        $query = "
             SELECT 
                us.id as id, 
                gr.id as group_id,
                gr.name as group_name,
                us.name as name, 
                us.email as email, 
                us.active as active, 
                us.image_file as image_file,
                us.image_path as image_path,
                us.created_user_id as created_user_id, 
                us.created_date as created_date, 
                crus.name as created_user_name,
                us.updated_user_id as updated_user_id, 
                us.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` us
                inner join `group` gr on us.group_id = gr.id
                inner join `user` upus on us.updated_user_id = upus.id 
                inner join `user` crus on us.created_user_id = crus.id
            ORDER BY us.created_date DESC
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
                us.id as id, 
                gr.id as group_id,
                us.name as name, 
                us.email as email, 
                us.active as active, 
                us.image_file as image_file,
                us.image_path as image_path,
                us.created_user_id as created_user_id, 
                us.created_date as created_date, 
                crus.name as created_user_name,
                us.updated_user_id as updated_user_id, 
                us.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` us 
                inner join `group` gr on us.group_id = gr.id
                inner join `user` upus on us.updated_user_id = upus.id 
                inner join `user` crus on us.created_user_id = crus.id
            WHERE us.id = :id";    

        $stmt = $this->conn->prepare( $query );
        $stmt->execute(['id' => $id]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search, $limit, $offset) {
        $query = "
            SELECT 
                us.id as id, 
                gr.id as group_id,
                gr.name as `group`,
                us.name as name,
                us.email as email, 
                us.active as active, 
                us.created_user_id as created_user_id, 
                us.created_date as created_date, 
                us.updated_user_id as updated_user_id, 
                us.updated_date as updated_date, 
                upus.name as updated_user_name 
            FROM 
                `" . $this->table_name . "` us 
                inner join `group` gr on us.group_id = gr.id
                inner join `user` upus on us.id = upus.id 
            WHERE 
                LOWER(us.name) LIKE LOWER('%$search%') 
                OR LOWER(us.email) LIKE LOWER('%$search%')
                OR LOWER(gr.name) LIKE LOWER('%$search%')
            ORDER BY us.name
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

    public function existsByEmail($email) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);

        return $stmt->rowCount() > 0;
    }

    public function existsByEmailWhenEdit($email, $id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE email = :email and id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'email' => $email,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }       
}
?>