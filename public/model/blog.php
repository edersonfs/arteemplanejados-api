<?php
class Blog
{

  private $conn;
  private $table_name = "blog";

  public $id;
  public $title;
  public $date;
  public $description;
  public $text;
  public $text_02;
  public $category;
  public $redactor;
  public $video;
  public $image_file;
  public $image_path;
  public $image_file_02;
  public $image_path_02;
  public $image_file_03;
  public $image_path_03;
  public $image_file_04;
  public $image_path_04;
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
            (title, `date`, description, `text`, text_02, category, redactor, video,
            image_file, image_path, image_file_02, image_path_02, image_file_03, image_path_03, image_file_04, image_path_04,
            created_date, created_user_id, updated_date, updated_user_id)
            VALUES
            (:title, :date, :description, :text, :text_02, :category, :redactor, :video,
            :image_file, :image_path, :image_file_02, :image_path_02, :image_file_03, :image_path_03, :image_file_04, :image_path_04,
            :created_date, :created_user_id, :updated_date, :updated_user_id)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':text', $data['text']);
    $stmt->bindParam(':text_02', $data['text_02']);
    $stmt->bindParam(':category', $data['category']);
    $stmt->bindParam(':redactor', $data['redactor']);
    $stmt->bindParam(':video', $data['video']);
    $stmt->bindParam(':image_file', $data['image_file']);
    $stmt->bindParam(':image_path', $data['image_path']);
    $stmt->bindParam(':image_file_02', $data['image_file_02']);
    $stmt->bindParam(':image_path_02', $data['image_path_02']);
    $stmt->bindParam(':image_file_03', $data['image_file_03']);
    $stmt->bindParam(':image_path_03', $data['image_path_03']);
    $stmt->bindParam(':image_file_04', $data['image_file_04']);
    $stmt->bindParam(':image_path_04', $data['image_path_04']);
    $stmt->bindParam(':created_date', $data['created_date']);
    $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':updated_date', $data['updated_date']);
    $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function update($data)
  {
    $query = "UPDATE `" . $this->table_name . "` SET
            title = :title,
            `date` = :date,
            description = :description,
            `text` = :text,
            text_02 = :text_02,
            category = :category,
            redactor = :redactor,
            video = :video,
            image_file = :image_file,
            image_path = :image_path,
            image_file_02 = :image_file_02,
            image_path_02 = :image_path_02,
            image_file_03 = :image_file_03,
            image_path_03 = :image_path_03,
            image_file_04 = :image_file_04,
            image_path_04 = :image_path_04,
            updated_date = :updated_date,
            updated_user_id = :updated_user_id
            WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':text', $data['text']);
    $stmt->bindParam(':text_02', $data['text_02']);
    $stmt->bindParam(':category', $data['category']);
    $stmt->bindParam(':redactor', $data['redactor']);
    $stmt->bindParam(':video', $data['video']);
    $stmt->bindParam(':image_file', $data['image_file']);
    $stmt->bindParam(':image_path', $data['image_path']);
    $stmt->bindParam(':image_file_02', $data['image_file_02']);
    $stmt->bindParam(':image_path_02', $data['image_path_02']);
    $stmt->bindParam(':image_file_03', $data['image_file_03']);
    $stmt->bindParam(':image_path_03', $data['image_path_03']);
    $stmt->bindParam(':image_file_04', $data['image_file_04']);
    $stmt->bindParam(':image_path_04', $data['image_path_04']);
    $stmt->bindParam(':updated_date', $data['updated_date']);
    $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
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
                bl.id,
                bl.title,
                bl.`date` as date,
                bl.description,
                bl.`text` as text,
                bl.text_02,
                bl.category,
                bl.redactor,
                bl.video,
                bl.image_file,
                bl.image_path,
                bl.image_file_02,
                bl.image_path_02,
                bl.image_file_03,
                bl.image_path_03,
                bl.image_file_04,
                bl.image_path_04,
                bl.created_user_id as created_user_id,
                bl.created_date as created_date,
                crus.name as created_user_name,
                bl.updated_user_id as updated_user_id,
                bl.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` bl
                inner join `user` upus on bl.updated_user_id = upus.id
                inner join `user` crus on bl.created_user_id = crus.id
            ORDER BY bl.title";

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
                bl.id,
                bl.title,
                bl.`date` as date,
                bl.description,
                bl.`text` as text,
                bl.text_02,
                bl.category,
                bl.redactor,
                bl.video,
                bl.image_file,
                bl.image_path,
                bl.image_file_02,
                bl.image_path_02,
                bl.image_file_03,
                bl.image_path_03,
                bl.image_file_04,
                bl.image_path_04,
                bl.created_user_id as created_user_id,
                bl.created_date as created_date,
                crus.name as created_user_name,
                bl.updated_user_id as updated_user_id,
                bl.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` bl
                inner join `user` upus on bl.updated_user_id = upus.id
                inner join `user` crus on bl.created_user_id = crus.id
            ORDER BY bl.created_date DESC
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
                bl.id,
                bl.title,
                bl.`date` as date,
                bl.description,
                bl.`text` as text,
                bl.text_02,
                bl.category,
                bl.redactor,
                bl.video,
                bl.image_file,
                bl.image_path,
                bl.image_file_02,
                bl.image_path_02,
                bl.image_file_03,
                bl.image_path_03,
                bl.image_file_04,
                bl.image_path_04,
                bl.created_user_id as created_user_id,
                bl.created_date as created_date,
                crus.name as created_user_name,
                bl.updated_user_id as updated_user_id,
                bl.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` bl
                inner join `user` upus on bl.updated_user_id = upus.id
                inner join `user` crus on bl.created_user_id = crus.id
             WHERE bl.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset)
  {
    $query = "
            SELECT
                bl.id,
                bl.title,
                bl.`date` as date,
                bl.description,
                bl.`text` as text,
                bl.text_02,
                bl.category,
                bl.redactor,
                bl.video,
                bl.image_file,
                bl.image_path,
                bl.image_file_02,
                bl.image_path_02,
                bl.image_file_03,
                bl.image_path_03,
                bl.image_file_04,
                bl.image_path_04,
                bl.created_user_id as created_user_id,
                bl.created_date as created_date,
                crus.name as created_user_name,
                bl.updated_user_id as updated_user_id,
                bl.updated_date as updated_date,
                upus.name as updated_user_name
            FROM
                `" . $this->table_name . "` bl
                inner join `user` upus on bl.updated_user_id = upus.id
                inner join `user` crus on bl.created_user_id = crus.id
            WHERE
                LOWER(IFNULL(bl.title, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(bl.description, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(bl.`text`, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(bl.text_02, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(bl.category, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(bl.redactor, '')) LIKE LOWER('%$search%')
                OR LOWER(IFNULL(bl.video, '')) LIKE LOWER('%$search%')
            ORDER BY bl.title
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
    $query = "SELECT * FROM `" . $this->table_name . "` WHERE title = :title and id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'title' => $title,
      'id' => $id
    ]);

    return $stmt->rowCount() > 0;
  }
}
