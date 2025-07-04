```php
<?php
require_once 'config.php';

function connectToDB() {
  global $conn;
  if (isset($conn)) return true;
  return false;
}

function getConn() {
  global $conn;
  return mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}
?>
```