<?php
class Work
{

  private $conn;
  private $table_name = "work";

  public $id;
  public $type;
  public $neighborhood;
  public $city;
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
            (type, neighborhood, city, image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:type, :neighborhood, :city, :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':type', $data['type']);
    $stmt->bindParam(':neighborhood', $data['neighborhood']);
    $stmt->bindParam(':city', $data['city']);
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
            type = :type,
            neighborhood = :neighborhood,
            city = :city,
            image_file = :image_file,
            image_path = :image_path,
            updated_user_id = :updated_user_id,
            updated_date = :updated_date
            WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':type', $data['type']);
    $stmt->bindParam(':neighborhood', $data['neighborhood']);
    $stmt->bindParam(':city', $data['city']);
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
                wk.id as id,
                wk.type as type,
                wk.neighborhood as neighborhood,
                wk.city as city,
                wk.image_file as image_file,
                wk.image_path as image_path,
                wk.created_user_id as created_user_id,
                wk.created_date as created_date,
                crus.name as created_user_name,
                wk.updated_user_id as updated_user_id,
                wk.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` wk
                inner join `user` upus on wk.updated_user_id = upus.id
                inner join `user` crus on wk.created_user_id = crus.id
            ORDER BY wk.id";

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
                wk.id as id,
                wk.type as type,
                wk.neighborhood as neighborhood,
                wk.city as city,
                wk.image_file as image_file,
                wk.image_path as image_path,
                wk.created_user_id as created_user_id,
                wk.created_date as created_date,
                crus.name as created_user_name,
                wk.updated_user_id as updated_user_id,
                wk.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` wk
                inner join `user` upus on wk.updated_user_id = upus.id
                inner join `user` crus on wk.created_user_id = crus.id
            ORDER BY wk.id
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
                wk.id as id,
                wk.type as type,
                wk.neighborhood as neighborhood,
                wk.city as city,
                wk.image_file as image_file,
                wk.image_path as image_path,
                wk.created_user_id as created_user_id,
                wk.created_date as created_date,
                crus.name as created_user_name,
                wk.updated_user_id as updated_user_id,
                wk.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` wk
                inner join `user` upus on wk.updated_user_id = upus.id
                inner join `user` crus on wk.created_user_id = crus.id
            WHERE wk.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset)
  {
    $query = "
            SELECT
                wk.id as id,
                wk.type as type,
                wk.neighborhood as neighborhood,
                wk.city as city,
                wk.image_file as image_file,
                wk.image_path as image_path,
                wk.created_user_id as created_user_id,
                wk.created_date as created_date,
                crus.name as created_user_name,
                wk.updated_user_id as updated_user_id,
                wk.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` wk
                inner join `user` upus on wk.updated_user_id = upus.id
                inner join `user` crus on wk.created_user_id = crus.id
            WHERE
                LOWER(IFNULL(wk.type, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(wk.neighborhood, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(wk.city, '')) LIKE LOWER('%$search%')
            ORDER BY wk.id
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function existsByTypeNeighborhoodCity($type, $neighborhood, $city)
  {
    $query = "SELECT * FROM `" . $this->table_name . "`
            WHERE `type` <=> :type
            AND neighborhood <=> :neighborhood
            AND city <=> :city";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'type' => $type,
      'neighborhood' => $neighborhood,
      'city' => $city
    ]);

    return $stmt->rowCount() > 0;
  }

  public function existsByTypeNeighborhoodCityWhenEdit($type, $neighborhood, $city, $id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "`
            WHERE `type` <=> :type
            AND neighborhood <=> :neighborhood
            AND city <=> :city
            AND id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'type' => $type,
      'neighborhood' => $neighborhood,
      'city' => $city,
      'id' => $id
    ]);

    return $stmt->rowCount() > 0;
  }
}
