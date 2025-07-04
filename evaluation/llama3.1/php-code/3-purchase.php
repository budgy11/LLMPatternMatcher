```php
<?php
function getProducts() {
  $db = new Database();
  $db->connect();
  $query = "SELECT * FROM products";
  $result = $db->query($query);
  $products = array();
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
  return $products;
}

function createOrder() {
  global $_POST;
  $db = new Database();
  $db->connect();
  $sql = "INSERT INTO orders (customer_name, email, order_date, total) VALUES (?, ?, NOW(), ?)";
  $stmt = $db->conn->prepare($sql);
  $stmt->bind_param("sss", $_POST['customer_name'], $_POST['email'], $_POST['total']);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

function addOrderItem() {
  global $_POST;
  $db = new Database();
  $db->connect();
  $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
  $stmt = $db->conn->prepare($sql);
  $stmt->bind_param("iis", $_POST['order_id'], $_POST['product_id'], $_POST['quantity']);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}
?>
```