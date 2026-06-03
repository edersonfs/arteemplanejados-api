<?php
class AboutUs
{

  private $conn;
  private $table_name = "about_us";

  public $id;
  public $title;
  public $little_description;
  public $description;
  public $content;
  public $video;
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
            (title, little_description, description, `content`, video, image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:title, :little_description, :description, :content, :video, :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':little_description', $data['little_description']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':content', $data['content']);
    $stmt->bindParam(':video', $data['video']);
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
            title = :title,
            little_description = :little_description,
            description = :description,
            `content` = :content,
            video = :video,
            image_file = :image_file,
            image_path = :image_path,
            updated_user_id = :updated_user_id,
            updated_date = :updated_date
            WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':little_description', $data['little_description']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':content', $data['content']);
    $stmt->bindParam(':video', $data['video']);
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
                au.id as id,
                au.title as title,
                au.little_description as little_description,
                au.description as description,
                au.`content` as content,
                au.video as video,
                au.image_file as image_file,
                au.image_path as image_path,
                au.created_user_id as created_user_id,
                au.created_date as created_date,
                crus.name as created_user_name,
                au.updated_user_id as updated_user_id,
                au.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` au
                inner join `user` upus on au.updated_user_id = upus.id
                inner join `user` crus on au.created_user_id = crus.id
            ORDER BY au.id";

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
                au.id as id,
                au.title as title,
                au.little_description as little_description,
                au.description as description,
                au.`content` as content,
                au.video as video,
                au.image_file as image_file,
                au.image_path as image_path,
                au.created_user_id as created_user_id,
                au.created_date as created_date,
                crus.name as created_user_name,
                au.updated_user_id as updated_user_id,
                au.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` au
                inner join `user` upus on au.updated_user_id = upus.id
                inner join `user` crus on au.created_user_id = crus.id
            ORDER BY au.created_date DESC
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
                au.id as id,
                au.title as title,
                au.little_description as little_description,
                au.description as description,
                au.`content` as content,
                au.video as video,
                au.image_file as image_file,
                au.image_path as image_path,
                au.created_user_id as created_user_id,
                au.created_date as created_date,
                crus.name as created_user_name,
                au.updated_user_id as updated_user_id,
                au.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` au
                inner join `user` upus on au.updated_user_id = upus.id
                inner join `user` crus on au.created_user_id = crus.id
            WHERE au.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset)
  {
    $query = "
            SELECT
                au.id as id,
                au.title as title,
                au.little_description as little_description,
                au.description as description,
                au.`content` as content,
                au.video as video,
                au.image_file as image_file,
                au.image_path as image_path,
                au.created_user_id as created_user_id,
                au.created_date as created_date,
                crus.name as created_user_name,
                au.updated_user_id as updated_user_id,
                au.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` au
                inner join `user` upus on au.updated_user_id = upus.id
                inner join `user` crus on au.created_user_id = crus.id
            WHERE
                LOWER(IFNULL(au.title, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(au.little_description, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(au.description, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(au.`content`, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(au.video, '')) LIKE LOWER('%$search%')
            ORDER BY au.id
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
