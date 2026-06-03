<?php
class MaterialHistorical
{

  private $conn;
  private $table_name = "material_historical";

  public $id;
  public $material_id;
  public $company_id;
  public $supplier_id;
  public $order_item_id;
  public $explanation;
  public $quantity;
  public $unit_cost;
  public $sales_price;
  public $stock;
  public $movement_type;
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
            (material_id, company_id, supplier_id, order_item_id, explanation, quantity, unit_cost, sales_price, stock,
            movement_type, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:material_id, :company_id, :supplier_id, :order_item_id, :explanation, :quantity, :unit_cost, :sales_price, :stock,
            :movement_type, :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':material_id', $data['material_id'], PDO::PARAM_INT);
    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindValue(
      ':supplier_id',
      $data['supplier_id'],
      $data['supplier_id'] === null || $data['supplier_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindValue(
      ':order_item_id',
      $data['order_item_id'],
      $data['order_item_id'] === null || $data['order_item_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindParam(':explanation', $data['explanation']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':unit_cost', $data['unit_cost']);
    $stmt->bindParam(':sales_price', $data['sales_price']);
    $stmt->bindParam(':stock', $data['stock']);
    $stmt->bindParam(':movement_type', $data['movement_type']);
    $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':created_date', $data['created_date']);
    $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':updated_date', $data['updated_date']);

    return $stmt->execute();
  }

  public function update($data)
  {
    $query = "UPDATE `" . $this->table_name . "`
                SET material_id = :material_id,
                    company_id = :company_id,
                    supplier_id = :supplier_id,
                    order_item_id = :order_item_id,
                    explanation = :explanation,
                    quantity = :quantity,
                    unit_cost = :unit_cost,
                    sales_price = :sales_price,
                    stock = :stock,
                    movement_type = :movement_type,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':material_id', $data['material_id'], PDO::PARAM_INT);
    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindValue(
      ':supplier_id',
      $data['supplier_id'],
      $data['supplier_id'] === null || $data['supplier_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindValue(
      ':order_item_id',
      $data['order_item_id'],
      $data['order_item_id'] === null || $data['order_item_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindParam(':explanation', $data['explanation']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':unit_cost', $data['unit_cost']);
    $stmt->bindParam(':sales_price', $data['sales_price']);
    $stmt->bindParam(':stock', $data['stock']);
    $stmt->bindParam(':movement_type', $data['movement_type']);
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

  /**
   * Prefer the CREATE snapshot row so material updates stay mirrored to cadastro history;
   * otherwise use the newest row so later movement entries are not rewritten in bulk.
   */
  public function findSnapshotRowForMaterialSync($material_id)
  {
    $stmt = $this->conn->prepare(
      "SELECT id, material_id, order_item_id, movement_type
             FROM `" . $this->table_name . "`
             WHERE material_id = :material_id AND movement_type = :create_type
             ORDER BY id DESC
             LIMIT 1"
    );
    $stmt->execute([
      'material_id' => $material_id,
      'create_type' => 'CREATE',
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      return $row;
    }

    $stmt = $this->conn->prepare(
      "SELECT id, material_id, order_item_id, movement_type
             FROM `" . $this->table_name . "`
             WHERE material_id = :material_id
             ORDER BY created_date DESC, id DESC
             LIMIT 1"
    );
    $stmt->execute(['material_id' => $material_id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
  }

  private function selectColumnsSql(): string
  {
    return "
                mh.id as id,
                mh.material_id as material_id,
                mat.name as material_name,
                mh.company_id as company_id,
                cmp.name as company_name,
                mh.supplier_id as supplier_id,
                sup.name as supplier_name,
                mh.order_item_id as order_item_id,
                ord.`number` as order_number,
                oi.description as order_item_description,
                mh.explanation as explanation,
                mh.quantity as quantity,
                mh.unit_cost as unit_cost,
                mh.sales_price as sales_price,
                mh.stock as stock,
                mh.movement_type as movement_type,
                mh.created_user_id as created_user_id,
                mh.created_date as created_date,
                crus.name as created_user_name,
                mh.updated_user_id as updated_user_id,
                mh.updated_date as updated_date,
                upus.name as updated_user_name,
                mat.image_file as image_file";
  }

  private function fromJoinSql(): string
  {
    return "
            FROM
                `" . $this->table_name . "` mh
                inner join `material` mat on mh.material_id = mat.id
                inner join `company` cmp on mh.company_id = cmp.id
                left join `supplier` sup on mh.supplier_id = sup.id
                left join `order_item` oi on mh.order_item_id = oi.id
                left join `order` ord on oi.order_id = ord.id
                inner join `user` upus on mh.updated_user_id = upus.id
                inner join `user` crus on mh.created_user_id = crus.id";
  }

  public function getAll($company_id, $material_id = null)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (mh.company_id = :company_id)
            AND (mat.id = :material_id OR :material_id = 0)
            ORDER BY mh.created_date DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['company_id' => $company_id, 'material_id' => $material_id ?? 0]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPagination($limit, $offset, $company_id, $material_id = null)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (mh.company_id = :company_id)
            AND (mat.id = :material_id OR :material_id = 0)
            ORDER BY mh.created_date DESC
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['company_id' => $company_id, 'material_id' => $material_id ?? 0]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getById($id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE mh.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset, $company_id)
  {
    $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (mh.company_id = :company_id)
                AND (
                    LOWER(IFNULL(mh.explanation, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(mh.movement_type, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(mh.quantity AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(mat.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(sup.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(oi.description, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(mh.stock AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(mh.unit_cost AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(mh.sales_price AS CHAR), '')) LIKE LOWER('%$search%')
                )
            ORDER BY mh.created_date DESC
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
}
