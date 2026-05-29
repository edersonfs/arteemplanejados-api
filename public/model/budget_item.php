<?php
class BudgetItem {

    private $conn;
    private $table_name = "budget_item";

    public $id;
    public $budget_id;
    public $material_id;
    public $description;
    public $quantity;
    public $width;
    public $height;
    public $unit_price;
    public $total;
    public $image_file;
    public $image_path;
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO `" . $this->table_name . "`
            (budget_id, material_id, description, quantity, width, height, unit_price, total,
            image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:budget_id, :material_id, :description, :quantity, :width, :height, :unit_price, :total,
            :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':budget_id', $data['budget_id'], PDO::PARAM_INT);
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
        $stmt->bindParam(':unit_price', $data['unit_price']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);

        return $stmt->execute();
    }

    public function update($data) {
        $query = "UPDATE `" . $this->table_name . "`
                SET budget_id = :budget_id,
                    material_id = :material_id,
                    description = :description,
                    quantity = :quantity,
                    width = :width,
                    height = :height,
                    unit_price = :unit_price,
                    total = :total,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':budget_id', $data['budget_id'], PDO::PARAM_INT);
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
        $stmt->bindParam(':unit_price', $data['unit_price']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':image_file', $data['image_file']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM `" . $this->table_name . "` WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

    private function selectColumnsSql(): string {
        return "
                bi.id as id,
                bi.budget_id as budget_id,
                ic.name as internal_client_name,
                bud.company_id as company_id,
                bud.`number` as budget_number,
                bi.material_id as material_id,
                mat.name as material_name,
                bi.description as description,
                bi.quantity as quantity,
                bi.width as width,
                bi.height as height,
                bi.unit_price as unit_price,
                bi.total as total,
                bi.image_file as image_file,
                bi.image_path as image_path,
                mat.image_file as material_image_file,
                bi.created_user_id as created_user_id,
                bi.created_date as created_date,
                crus.name as created_user_name,
                bi.updated_user_id as updated_user_id,
                bi.updated_date as updated_date,
                upus.name as updated_user_name";
    }

    private function fromJoinSql(): string {
        return "
            FROM
                `" . $this->table_name . "` bi
                inner join `budget` bud on bi.budget_id = bud.id
                inner join `internal_client` ic on bud.internal_client_id = ic.id
                left join `material` mat on bi.material_id = mat.id
                inner join `user` upus on bi.updated_user_id = upus.id
                inner join `user` crus on bi.created_user_id = crus.id";
    }

    public function getAll($budget_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (bi.budget_id = :budget_id or :budget_id is null)
            ORDER BY bi.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['budget_id' => $budget_id]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function getAllByMaterial($material_id) {
      $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
          WHERE (bi.material_id = :material_id or :material_id is null)
          ORDER BY bi.id";

      $stmt = $this->conn->prepare($query);
      $stmt->execute(['material_id' => $material_id]);

      $items = [];

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $items[] = $row;
      }

      return $items;
    }

    public function getPagination($limit, $offset, $budget_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (bi.budget_id = :budget_id or :budget_id is null)
            ORDER BY bi.created_date DESC
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['budget_id' => $budget_id]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function getPaginationByMaterial($limit, $offset, $material_id) {
      $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
          WHERE (bi.material_id = :material_id or :material_id is null)
          ORDER BY bi.created_date DESC
          LIMIT $limit OFFSET $offset";

      $stmt = $this->conn->prepare($query);
      $stmt->execute(['material_id' => $material_id]);

      $items = [];

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $items[] = $row;
      }

      return $items;
    }

    public function getById($id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE bi.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search, $limit, $offset, $budget_id) {
        $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (bi.budget_id = :budget_id)
                AND (
                    LOWER(IFNULL(bi.description, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(mat.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(bud.`number`, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(bi.quantity AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(bi.width AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(bi.height AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(bi.unit_price AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(bi.total AS CHAR), '')) LIKE LOWER('%$search%')
                )
            ORDER BY bi.id
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'budget_id' => $budget_id,
        ]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }
}
?>
