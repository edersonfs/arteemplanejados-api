<?php
class Order {

    private $conn;
    private $table_name = "order";

    public $id;
    public $company_id;
    public $internal_client_id;
    public $budget_id;
    public $number;
    public $status;
    public $start_date;
    public $install_date;
    public $delivery_date;
    public $total;
    public $notes;
    public $priority;
    public $estimated_days;
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
            (company_id, internal_client_id, budget_id, `number`, status,
            start_date, install_date, delivery_date, total, notes, priority, estimated_days,
            image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :internal_client_id, :budget_id, :number, :status,
            :start_date, :install_date, :delivery_date, :total, :notes, :priority, :estimated_days,
            :image_file, :image_path, :created_user_id, :created_date, :updated_user_id, :updated_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindValue(
            ':internal_client_id',
            $data['internal_client_id'],
            $data['internal_client_id'] === null || $data['internal_client_id'] === ''
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':budget_id',
            $data['budget_id'],
            $data['budget_id'] === null || $data['budget_id'] === ''
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );
        $stmt->bindParam(':number', $data['number']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':install_date', $data['install_date']);
        $stmt->bindParam(':delivery_date', $data['delivery_date']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':notes', $data['notes']);
        $stmt->bindParam(':priority', $data['priority']);
        $stmt->bindParam(':estimated_days', $data['estimated_days']);
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
                    internal_client_id = :internal_client_id,
                    budget_id = :budget_id,
                    `number` = :number,
                    status = :status,
                    start_date = :start_date,
                    install_date = :install_date,
                    delivery_date = :delivery_date,
                    total = :total,
                    notes = :notes,
                    priority = :priority,
                    estimated_days = :estimated_days,
                    image_file = :image_file,
                    image_path = :image_path,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindValue(
            ':internal_client_id',
            $data['internal_client_id'],
            $data['internal_client_id'] === null || $data['internal_client_id'] === ''
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':budget_id',
            $data['budget_id'],
            $data['budget_id'] === null || $data['budget_id'] === ''
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );
        $stmt->bindParam(':number', $data['number']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':install_date', $data['install_date']);
        $stmt->bindParam(':delivery_date', $data['delivery_date']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':notes', $data['notes']);
        $stmt->bindParam(':priority', $data['priority']);
        $stmt->bindParam(':estimated_days', $data['estimated_days']);
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
                ord.id as id,
                ord.company_id as company_id,
                cmp.name as company_name,
                ord.internal_client_id as internal_client_id,
                ic.name as internal_client_name,
                ord.budget_id as budget_id,
                bud.`number` as budget_number,
                ord.`number` as `number`,
                ord.status as status,
                ord.start_date as start_date,
                ord.install_date as install_date,
                ord.delivery_date as delivery_date,
                ord.total as total,
                ord.notes as notes,
                ord.priority as priority,
                ord.estimated_days as estimated_days,
                ord.image_file as image_file,
                ord.image_path as image_path,
                ord.created_user_id as created_user_id,
                ord.created_date as created_date,
                crus.name as created_user_name,
                ord.updated_user_id as updated_user_id,
                ord.updated_date as updated_date,
                upus.name as updated_user_name";
    }

    private function fromJoinSql(): string {
        return "
            FROM
                `" . $this->table_name . "` ord
                inner join `company` cmp on ord.company_id = cmp.id
                left join `internal_client` ic on ord.internal_client_id = ic.id
                left join `budget` bud on ord.budget_id = bud.id
                inner join `user` upus on ord.updated_user_id = upus.id
                inner join `user` crus on ord.created_user_id = crus.id";
    }

    public function getAll($group_id, $company_id, $internal_client_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (ord.company_id = :company_id OR :group_id = 1)
            AND (ord.internal_client_id = :internal_client_id OR :internal_client_id = 0)
            ORDER BY ord.`number`, ord.created_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'group_id' => $group_id,
            'company_id' => $company_id,
            'internal_client_id' => $internal_client_id,
        ]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function getPagination($limit, $offset, $group_id, $company_id, $internal_client_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (ord.company_id = :company_id OR :group_id = 1) AND (ord.internal_client_id = :internal_client_id OR :internal_client_id = 0)
            ORDER BY ord.created_date DESC
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['group_id' => $group_id, 'company_id' => $company_id, 'internal_client_id' => $internal_client_id]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function getById($id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE ord.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search, $limit, $offset, $group_id, $company_id) {
        $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (ord.company_id = :company_id OR :group_id = 1)
                AND (
                    LOWER(IFNULL(ord.`number`, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ord.status, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ord.notes, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(ord.priority AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(ord.estimated_days AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(ord.total AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ic.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(bud.`number`, '')) LIKE LOWER('%$search%')
                )
            ORDER BY ord.created_date DESC
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'group_id' => $group_id,
            'company_id' => $company_id,
        ]);

        $items = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    public function existsByNumber($number, $company_id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE `number` = :number AND company_id = :company_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'number' => $number,
            'company_id' => $company_id,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function existsByNumberWhenEdit($number, $id, $company_id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE `number` = :number AND company_id = :company_id AND id <> :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'number' => $number,
            'company_id' => $company_id,
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function existsByBudgetId($budget_id) {
        $query = "SELECT * FROM `" . $this->table_name . "` WHERE budget_id = :budget_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'budget_id' => $budget_id,
        ]);

        return $stmt->rowCount() > 0;
    }
}
?>
