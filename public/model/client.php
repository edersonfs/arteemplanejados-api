<?php
class Client
{

  private $conn;
  private $table_name = "client";

  public $id;
  public $name;
  public $date;
  public $address;
  public $phone;
  public $email;
  public $active;
  public $city;
  public $state;
  public $description;
  public $video;
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

  public function __construct($db)
  {
    $this->conn = $db;
  }

  /** @return array<int, array{file: string, path: string}> */
  public static function imageFieldPairs(): array
  {
    $pairs = [['file' => 'image_file', 'path' => 'image_path']];
    for ($i = 2; $i <= 15; $i++) {
      $s = '_' . str_pad((string) $i, 2, '0', STR_PAD_LEFT);
      $pairs[] = ['file' => 'image_file' . $s, 'path' => 'image_path' . $s];
    }
    return $pairs;
  }

  public function create($data)
  {
    $query = "INSERT INTO `" . $this->table_name . "`
            (`name`, `date`, address, phone, email, active, city, state, description, video,
            image_file, image_path, image_file_02, image_path_02, image_file_03, image_path_03,
            image_file_04, image_path_04, image_file_05, image_path_05, image_file_06, image_path_06,
            image_file_07, image_path_07, image_file_08, image_path_08, image_file_09, image_path_09,
            image_file_10, image_path_10, image_file_11, image_path_11, image_file_12, image_path_12,
            image_file_13, image_path_13, image_file_14, image_path_14, image_file_15, image_path_15,
            created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:name, :date, :address, :phone, :email, :active, :city, :state, :description, :video,
            :image_file, :image_path, :image_file_02, :image_path_02, :image_file_03, :image_path_03,
            :image_file_04, :image_path_04, :image_file_05, :image_path_05, :image_file_06, :image_path_06,
            :image_file_07, :image_path_07, :image_file_08, :image_path_08, :image_file_09, :image_path_09,
            :image_file_10, :image_path_10, :image_file_11, :image_path_11, :image_file_12, :image_path_12,
            :image_file_13, :image_path_13, :image_file_14, :image_path_14, :image_file_15, :image_path_15,
            :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':state', $data['state']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':video', $data['video']);

    foreach (self::imageFieldPairs() as $pair) {
      $stmt->bindValue(':' . $pair['file'], $data[$pair['file']]);
      $stmt->bindValue(':' . $pair['path'], $data[$pair['path']]);
    }

    $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':created_date', $data['created_date']);
    $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':updated_date', $data['updated_date']);

    return $stmt->execute();
  }

  public function update($data)
  {
    $query = "UPDATE `" . $this->table_name . "` SET
            `name` = :name,
            `date` = :date,
            address = :address,
            phone = :phone,
            email = :email,
            active = :active,
            city = :city,
            state = :state,
            description = :description,
            video = :video,
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

    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':state', $data['state']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':video', $data['video']);

    foreach (self::imageFieldPairs() as $pair) {
      $stmt->bindValue(':' . $pair['file'], $data[$pair['file']]);
      $stmt->bindValue(':' . $pair['path'], $data[$pair['path']]);
    }

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
                cl.id as id,
                cl.name as name,
                cl.`date` as date,
                cl.address as address,
                cl.phone as phone,
                cl.email as email,
                cl.active as active,
                cl.city as city,
                cl.state as state,
                cl.description as description,
                cl.video as video,
                cl.image_file as image_file,
                cl.image_path as image_path,
                cl.image_file_02 as image_file_02,
                cl.image_path_02 as image_path_02,
                cl.image_file_03 as image_file_03,
                cl.image_path_03 as image_path_03,
                cl.image_file_04 as image_file_04,
                cl.image_path_04 as image_path_04,
                cl.image_file_05 as image_file_05,
                cl.image_path_05 as image_path_05,
                cl.image_file_06 as image_file_06,
                cl.image_path_06 as image_path_06,
                cl.image_file_07 as image_file_07,
                cl.image_path_07 as image_path_07,
                cl.image_file_08 as image_file_08,
                cl.image_path_08 as image_path_08,
                cl.image_file_09 as image_file_09,
                cl.image_path_09 as image_path_09,
                cl.image_file_10 as image_file_10,
                cl.image_path_10 as image_path_10,
                cl.image_file_11 as image_file_11,
                cl.image_path_11 as image_path_11,
                cl.image_file_12 as image_file_12,
                cl.image_path_12 as image_path_12,
                cl.image_file_13 as image_file_13,
                cl.image_path_13 as image_path_13,
                cl.image_file_14 as image_file_14,
                cl.image_path_14 as image_path_14,
                cl.image_file_15 as image_file_15,
                cl.image_path_15 as image_path_15,
                cl.created_user_id as created_user_id,
                cl.created_date as created_date,
                crus.name as created_user_name,
                cl.updated_user_id as updated_user_id,
                cl.updated_date as updated_date,
                upus.name as updated_user_name";
  }

  private function fromJoinSql(): string
  {
    return "
            FROM
                `" . $this->table_name . "` cl
                inner join `user` upus on cl.updated_user_id = upus.id
                inner join `user` crus on cl.created_user_id = crus.id";
  }

  public function getAll()
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            ORDER BY cl.name";

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
            ORDER BY cl.created_date DESC
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
            WHERE cl.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset)
  {
    $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                LOWER(IFNULL(cl.name, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(cl.address, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(cl.phone, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(cl.email, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(cl.city, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(cl.state, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(cl.description, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(cl.video, '')) LIKE LOWER('%$search%')
            ORDER BY cl.name
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
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
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE email = :email and id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'email' => $email,
      'id' => $id
    ]);

    return $stmt->rowCount() > 0;
  }
}
