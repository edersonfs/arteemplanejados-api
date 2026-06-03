<?php
class ExpenseType
{

  private $conn;
  private $table_name = "expense_type";

  public $id;
  public $name;
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
            (name, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:name, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $data['name']);
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
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $data['name']);
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
                et.id as id,
                et.name as name,
                et.created_user_id as created_user_id,
                et.created_date as created_date,
                crus.name as created_user_name,
                et.updated_user_id as updated_user_id,
                et.updated_date as updated_date,
                upus.name as updated_user_name";
  }

  private function fromJoinSql(): string
  {
    return "
            FROM
                `" . $this->table_name . "` et
                inner join `user` upus on et.updated_user_id = upus.id
                inner join `user` crus on et.created_user_id = crus.id";
  }

  public function getAll()
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            ORDER BY et.name";

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
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            ORDER BY et.created_date DESC
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
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE et.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset)
  {
    $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                LOWER(IFNULL(et.name, '')) LIKE LOWER('%$search%')
            ORDER BY et.name
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getIdByName($name)
  {
    $query = "SELECT id FROM `" . $this->table_name . "` WHERE name = :name LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['name' => $name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? (int) $row['id'] : null;
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
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE name = :name AND id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'name' => $name,
      'id' => $id,
    ]);

    return $stmt->rowCount() > 0;
  }
}
