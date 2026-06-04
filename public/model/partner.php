<?php
class Partner
{

  private $conn;
  private $table_name = "partner";

  public $id;
  public $name;
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
            (name, image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:name, :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $data['name']);
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
    $query = "UPDATE `" . $this->table_name . "` SET
            name = :name,
            image_file = :image_file,
            image_path = :image_path,
            updated_user_id = :updated_user_id,
            updated_date = :updated_date
            WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $data['name']);
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

  public function getAll()
  {
    $query = "
            SELECT
                p.id as id,
                p.name as name,
                p.image_file as image_file,
                p.image_path as image_path,
                p.created_user_id as created_user_id,
                p.created_date as created_date,
                crus.name as created_user_name,
                p.updated_user_id as updated_user_id,
                p.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` p
                inner join `user` upus on p.updated_user_id = upus.id
                inner join `user` crus on p.created_user_id = crus.id
            ORDER BY p.name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPagination($limit, $offset)
  {
    $query = "
             SELECT
                p.id as id,
                p.name as name,
                p.image_file as image_file,
                p.image_path as image_path,
                p.created_user_id as created_user_id,
                p.created_date as created_date,
                crus.name as created_user_name,
                p.updated_user_id as updated_user_id,
                p.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` p
                inner join `user` upus on p.updated_user_id = upus.id
                inner join `user` crus on p.created_user_id = crus.id
            ORDER BY p.created_date DESC
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

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
                p.id as id,
                p.name as name,
                p.image_file as image_file,
                p.image_path as image_path,
                p.created_user_id as created_user_id,
                p.created_date as created_date,
                crus.name as created_user_name,
                p.updated_user_id as updated_user_id,
                p.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` p
                inner join `user` upus on p.updated_user_id = upus.id
                inner join `user` crus on p.created_user_id = crus.id
            WHERE p.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset)
  {
    $query = "
            SELECT
                p.id as id,
                p.name as name,
                p.image_file as image_file,
                p.image_path as image_path,
                p.created_user_id as created_user_id,
                p.created_date as created_date,
                crus.name as created_user_name,
                p.updated_user_id as updated_user_id,
                p.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` p
                inner join `user` upus on p.updated_user_id = upus.id
                inner join `user` crus on p.created_user_id = crus.id
            WHERE
                LOWER(IFNULL(p.name, '')) LIKE LOWER('%$search%')
            ORDER BY p.name
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function existsByName($name)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE name = :name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['name' => $name]);

    return $stmt->rowCount() > 0;
  }

  public function existsByNameWhenEdit($name, $id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE (name = :name) and (id <> :id)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'name' => $name,
      'id' => $id
    ]);

    return $stmt->rowCount() > 0;
  }
}
