<?php
class OrderItem
{

  private $conn;
  private $table_name = "order_item";

  public $id;
  public $company_id;
  public $order_id;
  public $material_id;
  public $description;
  public $quantity;
  public $width;
  public $height;
  public $color;
  public $unit_cost;
  public $total;
  public $discount_for_stock;
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
            (company_id, order_id, material_id, description, quantity, width, height, color, unit_cost, total, discount_for_stock,
            image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :order_id, :material_id, :description, :quantity, :width, :height, :color, :unit_cost, :total, :discount_for_stock,
            :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindParam(':order_id', $data['order_id'], PDO::PARAM_INT);
    $stmt->bindValue(
      ':material_id',
      $data['material_id'],
      $data['material_id'] === null || $data['material_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':width', $data['width']);
    $stmt->bindParam(':height', $data['height']);
    $stmt->bindParam(':color', $data['color']);
    $stmt->bindParam(':unit_cost', $data['unit_cost']);
    $stmt->bindParam(':total', $data['total']);
    $stmt->bindParam(':discount_for_stock', $data['discount_for_stock']);
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
                    order_id = :order_id,
                    material_id = :material_id,
                    description = :description,
                    quantity = :quantity,
                    width = :width,
                    height = :height,
                    color = :color,
                    unit_cost = :unit_cost,
                    total = :total,
                    discount_for_stock = :discount_for_stock,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindParam(':order_id', $data['order_id'], PDO::PARAM_INT);
    $stmt->bindValue(
      ':material_id',
      $data['material_id'],
      $data['material_id'] === null || $data['material_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':width', $data['width']);
    $stmt->bindParam(':height', $data['height']);
    $stmt->bindParam(':color', $data['color']);
    $stmt->bindParam(':unit_cost', $data['unit_cost']);
    $stmt->bindParam(':total', $data['total']);
    $stmt->bindParam(':discount_for_stock', $data['discount_for_stock']);
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
                oi.id as id,
                oi.company_id as company_id,
                cmp.name as company_name,
                oi.order_id as order_id,
                ord.`number` as order_number,
                oi.material_id as material_id,
                mat.name as material_name,
                oi.description as description,
                oi.quantity as quantity,
                oi.width as width,
                oi.height as height,
                oi.color as color,
                oi.unit_cost as unit_cost,
                oi.total as total,
                oi.discount_for_stock as discount_for_stock,
                oi.image_file as image_file,
                oi.image_path as image_path,
                oi.created_user_id as created_user_id,
                oi.created_date as created_date,
                crus.name as created_user_name,
                oi.updated_user_id as updated_user_id,
                oi.updated_date as updated_date,
                upus.name as updated_user_name";
  }

  private function fromJoinSql(): string
  {
    return "
            FROM
                `" . $this->table_name . "` oi
                inner join `company` cmp on oi.company_id = cmp.id
                inner join `order` ord on oi.order_id = ord.id
                left join `material` mat on oi.material_id = mat.id
                inner join `user` upus on oi.updated_user_id = upus.id
                inner join `user` crus on oi.created_user_id = crus.id";
  }

  public function getAll($company_id, $order_id = 0)
  {
    $orderClause = ((int) $order_id === 0) ? '' : ' AND oi.order_id = :order_id ';
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (oi.company_id = :company_id)
            $orderClause
            ORDER BY oi.id";

    $params = ['company_id' => $company_id];
    if ((int) $order_id !== 0) {
      $params['order_id'] = $order_id;
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getAllByMaterial($material_id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
          WHERE (oi.material_id = :material_id or :material_id is null)
          ORDER BY oi.id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['material_id' => $material_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPagination($limit, $offset, $company_id, $order_id = 0)
  {
    $orderClause = ((int) $order_id === 0) ? '' : ' AND oi.order_id = :order_id ';
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (oi.company_id = :company_id)
            $orderClause
            ORDER BY oi.created_date DESC
            LIMIT $limit OFFSET $offset";

    $params = ['company_id' => $company_id];
    if ((int) $order_id !== 0) {
      $params['order_id'] = $order_id;
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPaginationByMaterial($limit, $offset, $material_id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
          WHERE (oi.material_id = :material_id or :material_id is null)
          ORDER BY oi.created_date DESC
          LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['material_id' => $material_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getById($id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE oi.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset, $company_id, $order_id = 0)
  {
    $orderClause = ((int) $order_id === 0) ? '' : ' AND oi.order_id = :order_id ';
    $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (oi.company_id = :company_id)
                $orderClause
                AND (
                    LOWER(IFNULL(oi.description, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(mat.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(oi.color, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ord.`number`, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(oi.quantity AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(oi.width AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(oi.height AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(oi.unit_cost AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(oi.total AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(oi.discount_for_stock AS CHAR), '')) LIKE LOWER('%$search%')
                )
            ORDER BY oi.created_date DESC
            LIMIT $limit OFFSET $offset";

    $params = ['company_id' => $company_id];
    if ((int) $order_id !== 0) {
      $params['order_id'] = $order_id;
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }
}
