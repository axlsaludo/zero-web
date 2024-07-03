<?php
class User {
    private $conn;
    private $table_name = "users"; // Update table name to match your database

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserByEmail($email) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserStatus($id, $status) {
        $query = 'UPDATE ' . $this->table_name . ' SET is_active = :status WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Function to insert data into a specified table
    public function insertData($table, $columns, $values) {
        $columnsStr = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $query = "INSERT INTO $table ($columnsStr) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        foreach ($columns as $index => $column) {
            $stmt->bindParam(':' . $column, $values[$index]);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
