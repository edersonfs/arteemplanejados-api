<?php
class Property {
 
    // database connection and table name
    private $conn;
    private $table_name = "property";
 
    // object properties
    public $id;    
    public $name;
    public $address;
    public $rented;
    public $value;
    public $tenant;
    public $contact;
    public $rental_start_date;
    public $rental_end_date;
    public $category;
    public $comment;
    public $image_file;
    public $image_path;
    public $image_file_02;
    public $image_path_02;
    public $image_file_03;
    public $image_path_03;
    public $image_file_04;
    public $image_path_04;
    public $image_file_05;
    public $image_path_05;
    public $image_file_06;
    public $image_path_06;
    public $image_file_07;
    public $image_path_07;
    public $image_file_08;
    public $image_path_08;
    public $image_file_09;
    public $image_path_09;
    public $image_file_10;
    public $image_path_10;
    public $image_file_11;
    public $image_path_11;
    public $image_file_12;
    public $image_path_12;
    public $image_file_13;
    public $image_path_13;
    public $image_file_14;
    public $image_path_14;
    public $image_file_15;
    public $image_path_15;
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
            (name, address, rented, value, tenant, contact, rental_start_date, rental_end_date, category, comment,
            image_file, image_path, image_file_02, image_path_02, image_file_03, image_path_03, image_file_04, image_path_04,
            image_file_05, image_path_05, image_file_06, image_path_06, image_file_07, image_path_07, image_file_08, image_path_08,
            image_file_09, image_path_09, image_file_10, image_path_10, image_file_11, image_path_11, image_file_12, image_path_12,
            image_file_13, image_path_13, image_file_14, image_path_14, image_file_15, image_path_15,
            created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:name, :address, :rented, :value, :tenant, :contact, :rental_start_date, :rental_end_date, :category, :comment,
            :image_file, :image_path, :image_file_02, :image_path_02, :image_file_03, :image_path_03, :image_file_04, :image_path_04,
            :image_file_05, :image_path_05, :image_file_06, :image_path_06, :image_file_07, :image_path_07, :image_file_08, :image_path_08,
            :image_file_09, :image_path_09, :image_file_10, :image_path_10, :image_file_11, :image_path_11, :image_file_12, :image_path_12,
            :image_file_13, :image_path_13, :image_file_14, :image_path_14, :image_file_15, :image_path_15,
            :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        // main fields
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':rented', $data['rented'], PDO::PARAM_INT);
        $stmt->bindParam(':value', $data['value'], PDO::PARAM_STR);
        $stmt->bindParam(':tenant', $data['tenant']);
        $stmt->bindParam(':contact', $data['contact']);
        $stmt->bindParam(':rental_start_date', $data['rental_start_date']);
        $stmt->bindParam(':rental_end_date', $data['rental_end_date']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':comment', $data['comment']);

        // images loop to avoid repetition
        for ($i = 1; $i <= 15; $i++) {
            $suffix = $i === 1 ? "" : "_" . str_pad($i, 2, "0", STR_PAD_LEFT);
            $stmt->bindParam(":image_file{$suffix}", $data["image_file{$suffix}"]);
            $stmt->bindParam(":image_path{$suffix}", $data["image_path{$suffix}"]);
        }

        // audit fields
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);

        return $stmt->execute();
    }

    public function update($data) {
        $query = "UPDATE `" . $this->table_name . "` SET 
          name = :name,
          address = :address,
          rented = :rented,
          value = :value,
          tenant = :tenant,
          contact = :contact,
          rental_start_date = :rental_start_date,
          rental_end_date = :rental_end_date,
          category = :category,
          comment = :comment,
          image_file = :image_file,
          image_path = :image_path,
          image_file_02 = :image_file_02,
          image_path_02 = :image_path_02,
          image_file_03 = :image_file_03,
          image_path_03 = :image_path_03,
          image_file_04 = :image_file_04,
          image_path_04 = :image_path_04,
          image_file_05 = :image_file_05,
          image_path_05 = :image_path_05,
          image_file_06 = :image_file_06,
          image_path_06 = :image_path_06,
          image_file_07 = :image_file_07,
          image_path_07 = :image_path_07,
          image_file_08 = :image_file_08,
          image_path_08 = :image_path_08,
          image_file_09 = :image_file_09,
          image_path_09 = :image_path_09,
          image_file_10 = :image_file_10,
          image_path_10 = :image_path_10,
          image_file_11 = :image_file_11,
          image_path_11 = :image_path_11,
          image_file_12 = :image_file_12,
          image_path_12 = :image_path_12,
          image_file_13 = :image_file_13,
          image_path_13 = :image_path_13,
          image_file_14 = :image_file_14,
          image_path_14 = :image_path_14,
          image_file_15 = :image_file_15,
          image_path_15 = :image_path_15,
          updated_user_id = :updated_user_id,
          updated_date = :updated_date
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
                pr.address,
                pr.rented,
                pr.value,
                pr.tenant,
                pr.contact,
                pr.rental_start_date,
                pr.rental_end_date,
                pr.category,
                pr.comment,
                pr.image_file,
                pr.image_path,
                pr.image_file_02,
                pr.image_path_02,
                pr.image_file_03,
                pr.image_path_03,
                pr.image_file_04,
                pr.image_path_04,
                pr.image_file_05,
                pr.image_path_05,
                pr.image_file_06,
                pr.image_path_06,
                pr.image_file_07,
                pr.image_path_07,
                pr.image_file_08,
                pr.image_path_08,
                pr.image_file_09,
                pr.image_path_09,
                pr.image_file_10,
                pr.image_path_10,
                pr.image_file_11,
                pr.image_path_11,
                pr.image_file_12,
                pr.image_path_12,
                pr.image_file_13,
                pr.image_path_13,
                pr.image_file_14,
                pr.image_path_14,
                pr.image_file_15,
                pr.image_path_15,
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
                pr.address,
                pr.rented,
                pr.value,
                pr.tenant,
                pr.contact,
                pr.rental_start_date,
                pr.rental_end_date,
                pr.category,
                pr.comment,
                pr.image_file,
                pr.image_path,
                pr.image_file_02,
                pr.image_path_02,
                pr.image_file_03,
                pr.image_path_03,
                pr.image_file_04,
                pr.image_path_04,
                pr.image_file_05,
                pr.image_path_05,
                pr.image_file_06,
                pr.image_path_06,
                pr.image_file_07,
                pr.image_path_07,
                pr.image_file_08,
                pr.image_path_08,
                pr.image_file_09,
                pr.image_path_09,
                pr.image_file_10,
                pr.image_path_10,
                pr.image_file_11,
                pr.image_path_11,
                pr.image_file_12,
                pr.image_path_12,
                pr.image_file_13,
                pr.image_path_13,
                pr.image_file_14,
                pr.image_path_14,
                pr.image_file_15,
                pr.image_path_15,
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
            ORDER BY pr.created_date ASC
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
                pr.address,
                pr.rented,
                pr.value,
                pr.tenant,
                pr.contact,
                pr.rental_start_date,
                pr.rental_end_date,
                pr.category,
                pr.comment,
                pr.image_file,
                pr.image_path,
                pr.image_file_02,
                pr.image_path_02,
                pr.image_file_03,
                pr.image_path_03,
                pr.image_file_04,
                pr.image_path_04,
                pr.image_file_05,
                pr.image_path_05,
                pr.image_file_06,
                pr.image_path_06,
                pr.image_file_07,
                pr.image_path_07,
                pr.image_file_08,
                pr.image_path_08,
                pr.image_file_09,
                pr.image_path_09,
                pr.image_file_10,
                pr.image_path_10,
                pr.image_file_11,
                pr.image_path_11,
                pr.image_file_12,
                pr.image_path_12,
                pr.image_file_13,
                pr.image_path_13,
                pr.image_file_14,
                pr.image_path_14,
                pr.image_file_15,
                pr.image_path_15,
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
                pr.address,
                pr.rented,
                pr.value,
                pr.tenant,
                pr.contact,
                pr.rental_start_date,
                pr.rental_end_date,
                pr.category,
                pr.comment,
                pr.image_file,
                pr.image_path,
                pr.image_file_02,
                pr.image_path_02,
                pr.image_file_03,
                pr.image_path_03,
                pr.image_file_04,
                pr.image_path_04,
                pr.image_file_05,
                pr.image_path_05,
                pr.image_file_06,
                pr.image_path_06,
                pr.image_file_07,
                pr.image_path_07,
                pr.image_file_08,
                pr.image_path_08,
                pr.image_file_09,
                pr.image_path_09,
                pr.image_file_10,
                pr.image_path_10,
                pr.image_file_11,
                pr.image_path_11,
                pr.image_file_12,
                pr.image_path_12,
                pr.image_file_13,
                pr.image_path_13,
                pr.image_file_14,
                pr.image_path_14,
                pr.image_file_15,
                pr.image_path_15,
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
            WHERE pr.name LIKE LOWER(:search)";

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