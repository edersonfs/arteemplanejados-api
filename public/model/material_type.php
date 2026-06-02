<?php
class MaterialType {

    private $conn;
    private $table_name = "material_type";

    public $id;
    public $company_id;
    public $name;
    public $created_user_id;
    public $created_date;
    public $updated_user_id;
    public $updated_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO `" . $this->table_name . "`
            (company_id, name, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :name, :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':created_user_id', $data['created_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':created_date', $data['created_date']);
        $stmt->bindParam(':updated_user_id', $data['updated_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':updated_date', $data['updated_date']);

        return $stmt->execute();
    }

    public function update($data) {
        $query = "UPDATE `" . $this->table_name . "`
                SET company_id = :company_id,
                    name = :name,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
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
                mt.id as id,
                mt.company_id as company_id,
                cmp.name as company_name,
                mt.name as name,
                mt.created_user_id as created_user_id,
                mt.created_date as created_date,
                crus.name as created_user_name,
                mt.updated_user_id as updated_user_id,
                mt.updated_date as updated_date,
                upus.name as updated_user_name";
    }

    private function fromJoinSql(): string {
        return "
            FROM
                `" . $this->table_name . "` mt
                inner join `company` cmp on mt.company_id = cmp.id
                inner join `user` upus on mt.updated_user_id = upus.id
                inner join `user` crus on mt.created_user_id = crus.id";
    }

    public function getAll($company_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (mt.company_id = :company_id)
            ORDER BY mt.name";

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
            WHERE (mt.company_id = :company_id)
            ORDER BY mt.created_date DESC
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
            WHERE mt.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search, $limit, $offset, $company_id) {
        $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (mt.company_id = :company_id)
                AND (
                    LOWER(IFNULL(mt.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                )
            ORDER BY mt.name
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

    public function existsByName($name, $company_id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE name = :name AND company_id = :company_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'name' => $name,
            'company_id' => $company_id,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function existsByNameWhenEdit($name, $id, $company_id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE name = :name AND company_id = :company_id AND id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'name' => $name,
            'company_id' => $company_id,
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }
}
?>
