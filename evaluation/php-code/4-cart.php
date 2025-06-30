```php
<?php
require_once 'database.php';
session_start();
$product_id = $_GET["product_id"];

$conn->query("DELETE FROM carts WHERE product_id = '$product_id'");

header("Location: cart.php");
exit();
?>
```