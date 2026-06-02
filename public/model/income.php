<?php
class Income {

    private $conn;
    private $table_name = "income";

    public $id;
    public $company_id;
    public $internal_client_id;
    public $order_id;
    public $amount;
    public $due_date;
    public $payment_date;
    public $payment_method;
    public $status;
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
            (company_id, internal_client_id, order_id, amount, due_date, payment_date, payment_method, status,
            image_file, image_path, created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :internal_client_id, :order_id, :amount, :due_date, :payment_date, :payment_method, :status,
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
            ':order_id',
            $data['order_id'],
            $data['order_id'] === null || $data['order_id'] === ''
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':due_date', $data['due_date']);
        $stmt->bindParam(':payment_date', $data['payment_date']);
        $stmt->bindParam(':payment_method', $data['payment_method']);
        $stmt->bindParam(':status', $data['status']);
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
                    order_id = :order_id,
                    amount = :amount,
                    due_date = :due_date,
                    payment_date = :payment_date,
                    payment_method = :payment_method,
                    status = :status,
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
            ':order_id',
            $data['order_id'],
            $data['order_id'] === null || $data['order_id'] === ''
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':due_date', $data['due_date']);
        $stmt->bindParam(':payment_date', $data['payment_date']);
        $stmt->bindParam(':payment_method', $data['payment_method']);
        $stmt->bindParam(':status', $data['status']);
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
                inc.id as id,
                inc.company_id as company_id,
                cmp.name as company_name,
                inc.internal_client_id as internal_client_id,
                icl.name as internal_client_name,
                inc.order_id as order_id,
                ord.`number` as order_number,
                inc.amount as amount,
                inc.due_date as due_date,
                inc.payment_date as payment_date,
                inc.payment_method as payment_method,
                inc.status as status,
                inc.image_file as image_file,
                inc.image_path as image_path,
                inc.created_user_id as created_user_id,
                inc.created_date as created_date,
                crus.name as created_user_name,
                inc.updated_user_id as updated_user_id,
                inc.updated_date as updated_date,
                upus.name as updated_user_name";
    }

    private function fromJoinSql(): string {
        return "
            FROM
                `" . $this->table_name . "` inc
                inner join `company` cmp on inc.company_id = cmp.id
                left join `internal_client` icl on inc.internal_client_id = icl.id
                left join `order` ord on inc.order_id = ord.id
                inner join `user` upus on inc.updated_user_id = upus.id
                inner join `user` crus on inc.created_user_id = crus.id";
    }

    public function getAll($company_id) {
        $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (inc.company_id = :company_id)
            ORDER BY inc.due_date DESC, inc.created_date DESC";

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
            WHERE (inc.company_id = :company_id)
            ORDER BY inc.created_date DESC
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
            WHERE inc.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search, $limit, $offset, $company_id) {
        $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (inc.company_id = :company_id)
                AND (
                    LOWER(IFNULL(inc.status, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(inc.payment_method, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(icl.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(ord.`number`, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(inc.amount AS CHAR), '')) LIKE LOWER('%$search%')
                )
            ORDER BY inc.due_date DESC
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
