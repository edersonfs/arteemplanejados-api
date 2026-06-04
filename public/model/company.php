<?php
class Company
{

  private $conn;
  private $table_name = "company";

  public $id;
  public $name;
  public $cnpj;
  public $email;
  public $phone;
  public $image_file;
  public $image_path;
  public $created_user_id;
  public $created_date;
  public $updated_user_id;
  public $updated_date;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function create($data)
  {
    $query = "INSERT INTO `" . $this->table_name . "`
            (name, cnpj, email, phone, image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:name, :cnpj, :email, :phone, :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':cnpj', $data['cnpj']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':image_file', $data['image_file']);
    $stmt->bindParam(':image_path', $data['image_path']);
    $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':created_date', $data['created_date']);
    $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':updated_date', $data['updated_date']);

    return $stmt->execute();
  }

  public function update($data)
  {
    $query = "UPDATE `" . $this->table_name . "`
                SET name = :name,
                    cnpj = :cnpj,
                    email = :email,
                    phone = :phone,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':cnpj', $data['cnpj']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':image_file', $data['image_file']);
    $stmt->bindParam(':image_path', $data['image_path']);
    $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':updated_date', $data['updated_date']);
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function delete($id)
  {
    $query = "DELETE FROM `" . $this->table_name . "` WHERE id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
      return $stmt->rowCount() > 0;
    }

    return false;
  }

  public function getAll($group_id, $company_id)
  {
    $query = "
            SELECT
                co.id as id,
                co.name as name,
                co.cnpj as cnpj,
                co.email as email,
                co.phone as phone,
                co.image_file as image_file,
                co.image_path as image_path,
                co.created_user_id as created_user_id,
                co.created_date as created_date,
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id,
                co.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` co
                inner join `user` upus on co.updated_user_id = upus.id
                inner join `user` crus on co.created_user_id = crus.id
            WHERE (co.id = :company_id or :group_id = 1)
            ORDER BY co.name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['group_id' => $group_id, 'company_id' => $company_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPagination($limit, $offset, $group_id, $company_id)
  {
    $query = "
            SELECT
                co.id as id,
                co.name as name,
                co.cnpj as cnpj,
                co.email as email,
                co.phone as phone,
                co.image_file as image_file,
                co.image_path as image_path,
                co.created_user_id as created_user_id,
                co.created_date as created_date,
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id,
                co.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` co
                inner join `user` upus on co.updated_user_id = upus.id
                inner join `user` crus on co.created_user_id = crus.id
            WHERE (co.id = :company_id or :group_id = 1)
            ORDER BY co.name
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['group_id' => $group_id, 'company_id' => $company_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getById($id)
  {
    $query = "
            SELECT
                co.id as id,
                co.name as name,
                co.cnpj as cnpj,
                co.email as email,
                co.phone as phone,
                co.image_file as image_file,
                co.image_path as image_path,
                co.created_user_id as created_user_id,
                co.created_date as created_date,
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id,
                co.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` co
                inner join `user` upus on co.updated_user_id = upus.id
                inner join `user` crus on co.created_user_id = crus.id
            WHERE co.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset, $group_id, $company_id)
  {
    $query = "
            SELECT
                co.id as id,
                co.name as name,
                co.cnpj as cnpj,
                co.email as email,
                co.phone as phone,
                co.image_file as image_file,
                co.image_path as image_path,
                co.created_user_id as created_user_id,
                co.created_date as created_date,
                crus.name as created_user_name,
                co.updated_user_id as updated_user_id,
                co.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` co
                inner join `user` upus on co.updated_user_id = upus.id
                inner join `user` crus on co.created_user_id = crus.id
            WHERE
                LOWER(IFNULL(co.name, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(co.cnpj, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(co.email, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(co.phone, '')) LIKE LOWER('%$search%')
                AND (co.id = :company_id or :group_id = 1)
            ORDER BY co.name
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function existsByCnpj($cnpj)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE cnpj = :cnpj";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['cnpj' => $cnpj]);

    return $stmt->rowCount() > 0;
  }

  public function existsByCnpjWhenEdit($cnpj, $id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE cnpj = :cnpj AND id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'cnpj' => $cnpj,
      'id' => $id
    ]);

    return $stmt->rowCount() > 0;
  }

  public function existsByEmail($email)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE email = :email";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['email' => $email]);

    return $stmt->rowCount() > 0;
  }

  public function existsByEmailWhenEdit($email, $id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE email = :email AND id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'email' => $email,
      'id' => $id
    ]);

    return $stmt->rowCount() > 0;
  }
}
