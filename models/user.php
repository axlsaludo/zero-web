<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertData($table, $columns, $values) {
        $columnsStr = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $query = "INSERT INTO $table ($columnsStr) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        foreach ($columns as $column) {
            $stmt->bindParam(":$column", $values[$column]);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Function to fetch a single user by email
    public function getUserByEmail($email) {
        $query = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Function to update user status by id
    public function updateUserStatus($id, $status) {
        $query = 'UPDATE users SET is_active = :status WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Function to fetch all users
    public function getAllUsers() {
        $query = 'SELECT * FROM users';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to delete user by id
    public function deleteUser($id) {
        $query = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Additional CRUD operations can be added as needed
}
?>
