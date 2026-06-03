<?php
class Contact
{

  private $conn;
  private $table_name = "contact";

  public $id;
  public $title;
  public $button;
  public $image_file;
  public $image_path;
  public $address;
  public $contact_01;
  public $contact_02;
  public $email;
  public $instagram;
  public $youtube;
  public $site;
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
            (title, button, image_file, image_path, address, contact_01, contact_02, email, instagram, youtube, site, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:title, :button, :image_file, :image_path, :address, :contact_01, :contact_02, :email, :instagram, :youtube, :site, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':button', $data['button']);
    $stmt->bindParam(':image_file', $data['image_file']);
    $stmt->bindParam(':image_path', $data['image_path']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':contact_01', $data['contact_01']);
    $stmt->bindParam(':contact_02', $data['contact_02']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':instagram', $data['instagram']);
    $stmt->bindParam(':youtube', $data['youtube']);
    $stmt->bindParam(':site', $data['site']);

    $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':created_date', $data['created_date']);

    $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':updated_date', $data['updated_date']);

    return $stmt->execute();
  }

  public function update($data)
  {
    $query = "UPDATE `" . $this->table_name . "` SET
            title = :title,
            button = :button,
            image_file = :image_file,
            image_path = :image_path,
            address = :address,
            contact_01 = :contact_01,
            contact_02 = :contact_02,
            email = :email,
            instagram = :instagram,
            youtube = :youtube,
            site = :site,
            updated_user_id = :updated_user_id,
            updated_date = :updated_date
            WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':button', $data['button']);
    $stmt->bindParam(':image_file', $data['image_file']);
    $stmt->bindParam(':image_path', $data['image_path']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':contact_01', $data['contact_01']);
    $stmt->bindParam(':contact_02', $data['contact_02']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':instagram', $data['instagram']);
    $stmt->bindParam(':youtube', $data['youtube']);
    $stmt->bindParam(':site', $data['site']);

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
                c.id as id,
                c.title as title,
                c.button as button,
                c.image_file as image_file,
                c.image_path as image_path,
                c.address as address,
                c.contact_01 as contact_01,
                c.contact_02 as contact_02,
                c.email as email,
                c.instagram as instagram,
                c.youtube as youtube,
                c.site as site,
                c.created_user_id as created_user_id,
                c.created_date as created_date,
                crus.name as created_user_name,
                c.updated_user_id as updated_user_id,
                c.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` c
                inner join `user` upus on c.updated_user_id = upus.id
                inner join `user` crus on c.created_user_id = crus.id
            ORDER BY c.id";

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
                c.id as id,
                c.title as title,
                c.button as button,
                c.image_file as image_file,
                c.image_path as image_path,
                c.address as address,
                c.contact_01 as contact_01,
                c.contact_02 as contact_02,
                c.email as email,
                c.instagram as instagram,
                c.youtube as youtube,
                c.site as site,
                c.created_user_id as created_user_id,
                c.created_date as created_date,
                crus.name as created_user_name,
                c.updated_user_id as updated_user_id,
                c.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` c
                inner join `user` upus on c.updated_user_id = upus.id
                inner join `user` crus on c.created_user_id = crus.id
            ORDER BY c.id
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
                c.id as id,
                c.title as title,
                c.button as button,
                c.image_file as image_file,
                c.image_path as image_path,
                c.address as address,
                c.contact_01 as contact_01,
                c.contact_02 as contact_02,
                c.email as email,
                c.instagram as instagram,
                c.youtube as youtube,
                c.site as site,
                c.created_user_id as created_user_id,
                c.created_date as created_date,
                crus.name as created_user_name,
                c.updated_user_id as updated_user_id,
                c.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` c
                inner join `user` upus on c.updated_user_id = upus.id
                inner join `user` crus on c.created_user_id = crus.id
            WHERE c.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset)
  {
    $query = "
            SELECT
                c.id as id,
                c.title as title,
                c.button as button,
                c.image_file as image_file,
                c.image_path as image_path,
                c.address as address,
                c.contact_01 as contact_01,
                c.contact_02 as contact_02,
                c.email as email,
                c.instagram as instagram,
                c.youtube as youtube,
                c.site as site,
                c.created_user_id as created_user_id,
                c.created_date as created_date,
                crus.name as created_user_name,
                c.updated_user_id as updated_user_id,
                c.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` c
                inner join `user` upus on c.updated_user_id = upus.id
                inner join `user` crus on c.created_user_id = crus.id
            WHERE
                LOWER(IFNULL(c.title, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.button, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.address, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.contact_01, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.contact_02, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.email, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.instagram, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.youtube, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(c.site, '')) LIKE LOWER('%$search%')
            ORDER BY c.id
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function existsByTitle($title)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE title = :title";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['title' => $title]);

    return $stmt->rowCount() > 0;
  }

  public function existsByTitleWhenEdit($title, $id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE (title = :title) and (id <> :id)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'title' => $title,
      'id' => $id
    ]);

    return $stmt->rowCount() > 0;
  }
}
