<?php
class Invoicing
{

  private $conn;
  private $table_name = "invoicing";

  public $id;
  public $company_id;
  public $month;
  public $year;
  public $total_income;
  public $total_expense;
  public $total_profit;
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
            (company_id, month, year, total_income, total_expense, total_profit,
            created_user_id, created_date, updated_user_id, updated_date)
            VALUES
            (:company_id, :month, :year, :total_income, :total_expense, :total_profit,
            :created_user_id, :created_date, :updated_user_id, :updated_date)";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindParam(':month', $data['month'], PDO::PARAM_INT);
    $stmt->bindParam(':year', $data['year'], PDO::PARAM_INT);
    $stmt->bindParam(':total_income', $data['total_income']);
    $stmt->bindParam(':total_expense', $data['total_expense']);
    $stmt->bindParam(':total_profit', $data['total_profit']);
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
                    month = :month,
                    year = :year,
                    total_income = :total_income,
                    total_expense = :total_expense,
                    total_profit = :total_profit,
                    updated_user_id = :updated_user_id,
                    updated_date = :updated_date
                WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
    $stmt->bindParam(':month', $data['month'], PDO::PARAM_INT);
    $stmt->bindParam(':year', $data['year'], PDO::PARAM_INT);
    $stmt->bindParam(':total_income', $data['total_income']);
    $stmt->bindParam(':total_expense', $data['total_expense']);
    $stmt->bindParam(':total_profit', $data['total_profit']);
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
                inv.id as id,
                inv.company_id as company_id,
                cmp.name as company_name,
                inv.month as month,
                inv.year as year,
                inv.total_income as total_income,
                inv.total_expense as total_expense,
                inv.total_profit as total_profit,
                inv.created_user_id as created_user_id,
                inv.created_date as created_date,
                crus.name as created_user_name,
                inv.updated_user_id as updated_user_id,
                inv.updated_date as updated_date,
                upus.name as updated_user_name";
  }

  private function fromJoinSql(): string
  {
    return "
            FROM
                `" . $this->table_name . "` inv
                inner join `company` cmp on inv.company_id = cmp.id
                inner join `user` upus on inv.updated_user_id = upus.id
                inner join `user` crus on inv.created_user_id = crus.id";
  }

  public function getAll($company_id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (inv.company_id = :company_id)
            ORDER BY inv.year DESC, inv.month DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['company_id' => $company_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getPagination($limit, $offset, $company_id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE (inv.company_id = :company_id)
            ORDER BY inv.year DESC, inv.month DESC
            LIMIT $limit OFFSET $offset";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['company_id' => $company_id]);

    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }

    return $items;
  }

  public function getById($id)
  {
    $query = "SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE inv.id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function search($search, $limit, $offset, $company_id)
  {
    $query = "
            SELECT " . $this->selectColumnsSql() . $this->fromJoinSql() . "
            WHERE
                (inv.company_id = :company_id)
                AND (
                    LOWER(IFNULL(CAST(inv.month AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(inv.year AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(inv.total_income AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(inv.total_expense AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(CAST(inv.total_profit AS CHAR), '')) LIKE LOWER('%$search%')
                    OR LOWER(IFNULL(cmp.name, '')) LIKE LOWER('%$search%')
                )
            ORDER BY inv.year DESC, inv.month DESC
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

  public function existsByMonthYear($month, $year, $company_id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "`
            WHERE month = :month AND year = :year AND company_id = :company_id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'month' => $month,
      'year' => $year,
      'company_id' => $company_id,
    ]);

    return $stmt->rowCount() > 0;
  }

  public function existsByMonthYearWhenEdit($month, $year, $id, $company_id)
  {
    $query = "SELECT * FROM `" . $this->table_name . "`
            WHERE month = :month AND year = :year AND company_id = :company_id AND id <> :id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      'month' => $month,
      'year' => $year,
      'company_id' => $company_id,
      'id' => $id,
    ]);

    return $stmt->rowCount() > 0;
  }
}
