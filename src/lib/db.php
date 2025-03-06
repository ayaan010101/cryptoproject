<?php
// Database connection configuration for shared hosting

class Database {
    private $host = 'localhost'; // Your database host
    private $username = 'db_username'; // Your database username
    private $password = 'db_password'; // Your database password
    private $database = 'crypto_trading'; // Your database name
    private $conn;
    
    // Constructor to establish database connection
    public function __construct() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            // Set charset to ensure proper encoding
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    
    // Get database connection
    public function getConnection() {
        return $this->conn;
    }
    
    // Close database connection
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    
    // Execute a query with prepared statements
    public function query($sql, $params = [], $types = "") {
        try {
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Query preparation failed: " . $this->conn->error);
            }
            
            // Bind parameters if they exist
            if (!empty($params)) {
                // If types string is not provided, generate it
                if (empty($types)) {
                    $types = $this->generateParamTypes($params);
                }
                
                // Convert params array to references for bind_param
                $bindParams = array($types);
                foreach ($params as $key => $value) {
                    $bindParams[] = &$params[$key];
                }
                
                call_user_func_array(array($stmt, 'bind_param'), $bindParams);
            }
            
            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Query execution failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Database Query Error: " . $e->getMessage());
            return false;
        }
    }
    
    // Generate parameter types string for bind_param
    private function generateParamTypes($params) {
        $types = "";
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= "i"; // integer
            } elseif (is_float($param)) {
                $types .= "d"; // double/float
            } elseif (is_string($param)) {
                $types .= "s"; // string
            } else {
                $types .= "b"; // blob
            }
        }
        return $types;
    }
    
    // Fetch all rows from a result
    public function fetchAll($result) {
        $rows = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
    
    // Fetch a single row from a result
    public function fetchOne($result) {
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    // Get the ID of the last inserted row
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }
    
    // Escape a string to prevent SQL injection
    public function escapeString($string) {
        return $this->conn->real_escape_string($string);
    }
}
