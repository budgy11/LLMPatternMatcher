```php
<?php
class Database {
  private $conn;

  public function connect() {
    $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  public function query($sql) {
    return $this->conn->query($sql);
  }

  public function getResults() {
    $results = array();
    while ($row = $this->conn->fetch_assoc()) {
      $results[] = $row;
    }
    return $results;
  }

  public function closeConnection() {
    $this->conn->close();
  }
}
?>
```