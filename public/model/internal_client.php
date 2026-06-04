<?php
class InternalClient
{

  private $conn;
  private $table_name = "internal_client";

  public $id;
  public $company_id;
  public $name;
  public $cpf_cnpj;
  public $address;
  public $city;
  public $state;
  public $phone;
  public $email;
  public $notes;
  public $active;
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
            (company_id, name, cpf_cnpj, address, city, state, phone, email, notes, active,
            image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :name, :cpf_cnpj, :address, :city, :state, :phone, :email, :notes, :active,
            :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':cpf_cnpj', $data['cpf_cnpj']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':state', $data['state']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':notes', $data['notes']);
    $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
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
                SET company_id = :company_id,
                    name = :name,
                    cpf_cnpj = :cpf_cnpj,
                    address = :address,
                    city = :city,
                    state = :state,
                    phone = :phone,
                    email = :email,
                    notes = :notes,
                    active = :active,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':cpf_cnpj', $data['cpf_cnpj']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':state', $data['state']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':notes', $data['notes']);
    $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
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

  private function selectColumnsSql(): string
  {
    return "
                ic.id as id,
                ic.company_id as company_id,
                cmp.name as company_name,
                ic.name as name,
                ic.cpf_cnpj as cpf_cnpj,
                ic.address as address,
                ic.city as city,
                ic.state as state,
                ic.phone as phone,
                ic.email as email,
                ic.notes as notes,
                ic.active as active,
                ic.image_file as image_file,
                ic.image_path as image_path,
                ic.created_user_id as created_user_id,
                ic.created_date as created_date,
                crus.name as created_user_name,
                ic.updated_user_id as updated_user_id,
                ic.updated_date as updated_date,
                upus.name as updated_user_name";
  }

  private function fromJoinSql(): string
  {
    return "
            FROM
                `" . $this->table_name . "` ic
                inner join `company` cmp on ic.company_id = cmp.id
                inner join `user` upus on ic.updated_user_id = upus.id
                inner join `user` crus on ic.created_user_id = crus.id";
  }

  public function getAll($company_id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (ic.company_id = :company_id)
            ORDER BY ic.name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['company_id' => $company_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPagination($limit, $offset, $company_id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (ic.company_id = :company_id)
            ORDER BY ic.created_date DESC
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['company_id' => $company_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getById($id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE ic.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset, $company_id)
  {
    $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (ic.company_id = :company_id)
                AND (
                    LOWER(IFNULL(ic.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.cpf_cnpj, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.email, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.phone, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.address, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.city, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.state, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.notes, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                )
            ORDER BY ic.name
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'company_id' => $company_id,
    ]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function existsByCpfCnpj($cpf_cnpj, $company_id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE cpf_cnpj = :cpf_cnpj AND company_id = :company_id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'cpf_cnpj' => $cpf_cnpj,
      'company_id' => $company_id,
    ]);

    return $stmt->rowCount() > 0;
  }

  public function existsByCpfCnpjWhenEdit($cpf_cnpj, $id, $company_id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE cpf_cnpj = :cpf_cnpj AND company_id = :company_id AND id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'cpf_cnpj' => $cpf_cnpj,
      'company_id' => $company_id,
      'id' => $id,
    ]);

    return $stmt->rowCount() > 0;
  }

  public function existsByEmail($email, $company_id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE email = :email AND company_id = :company_id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'email' => $email,
      'company_id' => $company_id,
    ]);

    return $stmt->rowCount() > 0;
  }

  public function existsByEmailWhenEdit($email, $id, $company_id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE email = :email AND company_id = :company_id AND id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'email' => $email,
      'company_id' => $company_id,
      'id' => $id,
    ]);

    return $stmt->rowCount() > 0;
  }
}
