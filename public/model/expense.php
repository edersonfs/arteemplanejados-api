<?php
class Expense
{

  private $conn;
  private $table_name = "expense";

  public $id;
  public $company_id;
  public $order_id;
  public $order_item_id;
  public $supplier_id;
  public $material_id;
  public $expense_type_id;
  public $description;
  public $quantity;
  public $value;
  public $expense_date;
  public $payment_date;
  public $status;
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
            (company_id, order_id, order_item_id, supplier_id, material_id, expense_type_id, description, quantity, value,
            expense_date, payment_date, status, image_file, image_path,
            created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :order_id, :order_item_id, :supplier_id, :material_id, :expense_type_id, :description, :quantity, :value,
            :expense_date, :payment_date, :status, :image_file, :image_path,
            :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindValue(
      ':order_id',
      $data['order_id'],
      $data['order_id'] === null || $data['order_id'] === ''
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
    $stmt->bindValue(
      ':supplier_id',
      $data['supplier_id'],
      $data['supplier_id'] === null || $data['supplier_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindValue(
      ':material_id',
      $data['material_id'],
      $data['material_id'] === null || $data['material_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindParam(':expense_type_id', $data['expense_type_id'], PDO::PARAM_INT);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':value', $data['value']);
    $stmt->bindParam(':expense_date', $data['expense_date']);
    $stmt->bindParam(':payment_date', $data['payment_date']);
    $stmt->bindParam(':status', $data['status']);
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
                    order_item_id = :order_item_id,
                    supplier_id = :supplier_id,
                    material_id = :material_id,
                    expense_type_id = :expense_type_id,
                    description = :description,
                    quantity = :quantity,
                    value = :value,
                    expense_date = :expense_date,
                    payment_date = :payment_date,
                    status = :status,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindValue(
      ':order_id',
      $data['order_id'],
      $data['order_id'] === null || $data['order_id'] === ''
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
    $stmt->bindValue(
      ':supplier_id',
      $data['supplier_id'],
      $data['supplier_id'] === null || $data['supplier_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindValue(
      ':material_id',
      $data['material_id'],
      $data['material_id'] === null || $data['material_id'] === ''
        ? PDO::PARAM_NULL
        : PDO::PARAM_INT
    );
    $stmt->bindParam(':expense_type_id', $data['expense_type_id'], PDO::PARAM_INT);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':value', $data['value']);
    $stmt->bindParam(':expense_date', $data['expense_date']);
    $stmt->bindParam(':payment_date', $data['payment_date']);
    $stmt->bindParam(':status', $data['status']);
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

  public function deleteByOrderItemId($order_item_id)
  {
    $query = "DELETE FROM `" . $this->table_name . "` WHERE order_item_id = :order_item_id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':order_item_id', $order_item_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  private function selectColumnsSql(): string
  {
    return "
                exp.id as id,
                exp.company_id as company_id,
                cmp.name as company_name,
                exp.order_id as order_id,
                ord.`number` as order_number,
                exp.order_item_id as order_item_id,
                oit.description as order_item_description,
                exp.supplier_id as supplier_id,
                sup.name as supplier_name,
                exp.material_id as material_id,
                mat.name as material_name,
                exp.expense_type_id as expense_type_id,
                etp.name as expense_type_name,
                exp.description as description,
                exp.quantity as quantity,
                exp.value as value,
                exp.expense_date as expense_date,
                exp.payment_date as payment_date,
                exp.status as status,
                exp.image_file as image_file,
                exp.image_path as image_path,
                exp.created_user_id as created_user_id,
                exp.created_date as created_date,
                crus.name as created_user_name,
                exp.updated_user_id as updated_user_id,
                exp.updated_date as updated_date,
                upus.name as updated_user_name";
  }

  private function fromJoinSql(): string
  {
    return "
            FROM
                `" . $this->table_name . "` exp
                inner join `company` cmp on exp.company_id = cmp.id
                left join `order` ord on exp.order_id = ord.id
                left join `order_item` oit on exp.order_item_id = oit.id
                left join `supplier` sup on exp.supplier_id = sup.id
                left join `material` mat on exp.material_id = mat.id
                inner join `expense_type` etp on exp.expense_type_id = etp.id
                inner join `user` upus on exp.updated_user_id = upus.id
                inner join `user` crus on exp.created_user_id = crus.id";
  }

  public function getAll($company_id, $order_id = 0, $material_id = 0, $order_item_id = 0)
  {
    $orderClause = ((int) $order_id === 0) ? '' : ' AND exp.order_id = :order_id ';
    $materialClause = ((int) $material_id === 0) ? '' : ' AND exp.material_id = :material_id ';
    $orderItemClause = ((int) $order_item_id === 0) ? '' : ' AND exp.order_item_id = :order_item_id ';
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (exp.company_id = :company_id)
            $orderClause
            $materialClause
            $orderItemClause
            ORDER BY exp.expense_date DESC, exp.created_date DESC";

    $params = ['company_id' => $company_id];
    if ((int) $order_id !== 0) {
      $params['order_id'] = $order_id;
    }
    if ((int) $material_id !== 0) {
      $params['material_id'] = $material_id;
    }
    if ((int) $order_item_id !== 0) {
      $params['order_item_id'] = $order_item_id;
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPagination($limit, $offset, $company_id, $order_id = 0, $material_id = 0, $order_item_id = 0)
  {
    $orderClause = ((int) $order_id === 0) ? '' : ' AND exp.order_id = :order_id ';
    $materialClause = ((int) $material_id === 0) ? '' : ' AND exp.material_id = :material_id ';
    $orderItemClause = ((int) $order_item_id === 0) ? '' : ' AND exp.order_item_id = :order_item_id ';
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (exp.company_id = :company_id)
            $orderClause
            $materialClause
            $orderItemClause
            ORDER BY exp.created_date DESC
            LIMIT $limit OFFSET $offset";

    $params = ['company_id' => $company_id];
    if ((int) $order_id !== 0) {
      $params['order_id'] = $order_id;
    }
    if ((int) $material_id !== 0) {
      $params['material_id'] = $material_id;
    }
    if ((int) $order_item_id !== 0) {
      $params['order_item_id'] = $order_item_id;
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getById($id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE exp.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset, $company_id, $order_id = 0, $material_id = 0, $order_item_id = 0)
  {
    $orderClause = ((int) $order_id === 0) ? '' : ' AND exp.order_id = :order_id ';
    $materialClause = ((int) $material_id === 0) ? '' : ' AND exp.material_id = :material_id ';
    $orderItemClause = ((int) $order_item_id === 0) ? '' : ' AND exp.order_item_id = :order_item_id ';
    $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (exp.company_id = :company_id)
                $orderClause
                $materialClause
                $orderItemClause
                AND (
                    LOWER(IFNULL(exp.description, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(exp.status, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(etp.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(sup.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(mat.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(oit.description, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ord.`number`, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(exp.quantity AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(exp.value AS CHAR), '')) LIKE LOWER('%$search%')
                )
            ORDER BY exp.expense_date DESC
            LIMIT $limit OFFSET $offset";

    $params = ['company_id' => $company_id];
    if ((int) $order_id !== 0) {
      $params['order_id'] = $order_id;
    }
    if ((int) $material_id !== 0) {
      $params['material_id'] = $material_id;
    }
    if ((int) $order_item_id !== 0) {
      $params['order_item_id'] = $order_item_id;
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function findMaterialPurchaseExpenseId($material_id, $expense_type_id)
  {
    $query = "SELECT id FROM `" . $this->table_name . "`
            WHERE material_id = :material_id AND expense_type_id = :expense_type_id
            ORDER BY id ASC
            LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'material_id' => $material_id,
      'expense_type_id' => $expense_type_id,
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? (int) $row['id'] : null;
  }

  public function findMaterialPurchaseExpenseIdByOrderItemId($order_item_id, $expense_type_id)
  {
    $query = "SELECT id FROM `" . $this->table_name . "`
            WHERE order_item_id = :order_item_id AND expense_type_id = :expense_type_id
            ORDER BY id DESC
            LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'order_item_id' => $order_item_id,
      'expense_type_id' => $expense_type_id,
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? (int) $row['id'] : null;
  }
}
