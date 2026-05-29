<?php
class Material {

    private $conn;
    private $table_name = "material";

    public $id;
    public $company_id;
    public $supplier_id;
    public $name;
    public $description;
    public $unit_cost;
    public $sale_price;
    public $stock;
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
            (company_id, supplier_id, name, description, unit_cost, sale_price, stock,
            image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :supplier_id, :name, :description, :unit_cost, :sale_price, :stock,
            :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindParam(':supplier_id', $data['supplier_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':unit_cost', $data['unit_cost']);
        $stmt->bindParam(':sale_price', $data['sale_price']);
        $stmt->bindParam(':stock', $data['stock']);
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
                SET company_id = :company_id,
                    supplier_id = :supplier_id,
                    name = :name,
                    description = :description,
                    unit_cost = :unit_cost,
                    sale_price = :sale_price,
                    stock = :stock,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindParam(':supplier_id', $data['supplier_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':unit_cost', $data['unit_cost']);
        $stmt->bindParam(':sale_price', $data['sale_price']);
        $stmt->bindParam(':stock', $data['stock']);
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
                mat.id as id,
                mat.company_id as company_id,
                cmp.name as company_name,
                mat.supplier_id as supplier_id,
                sup.name as supplier_name,
                mat.name as name,
                mat.description as description,
                mat.unit_cost as unit_cost,
                mat.sale_price as sale_price,
                mat.stock as stock,
                mat.image_file as image_file,
                mat.image_path as image_path,
                mat.created_user_id as created_user_id,
                mat.created_date as created_date,
                crus.name as created_user_name,
                mat.updated_user_id as updated_user_id,
                mat.updated_date as updated_date,
                upus.name as updated_user_name";
    }

    private function fromJoinSql(): string {
        return "
            FROM
                `" . $this->table_name . "` mat
                inner join `company` cmp on mat.company_id = cmp.id
                inner join `supplier` sup on mat.supplier_id = sup.id
                inner join `user` upus on mat.updated_user_id = upus.id
                inner join `user` crus on mat.created_user_id = crus.id";
    }

    public function getAll($company_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (mat.company_id = :company_id)
            ORDER BY mat.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['company_id' => $company_id]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function getPagination($limit, $offset, $company_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (mat.company_id = :company_id)
            ORDER BY mat.created_date DESC
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['company_id' => $company_id]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function getById($id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE mat.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search, $limit, $offset, $company_id) {
        $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (mat.company_id = :company_id)
                AND (
                    LOWER(IFNULL(mat.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(mat.description, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(sup.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                )
            ORDER BY mat.name
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
?>
