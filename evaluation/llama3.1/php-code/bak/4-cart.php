```php
<?php
// Include database connection file
require_once 'db.php';

// Get all items in cart for current user
$cart_items = array();
$sql = "SELECT * FROM cart WHERE user_id = $_SESSION[user_id]";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
  $product_id = $row['product_id'];
  $item = get_product_info($product_id);
  $cart_items[] = array(
    'id' => $product_id,
    'name' => $item['name'],
    'description' => $item['description'],
    'price' => $item['price'],
    'quantity' => $row['quantity']
  );
}

// Update cart with new quantities
foreach ($_POST['cart_items'] as $item) {
  $product_id = $item['id'];
  $new_quantity = $item['quantity'];
  $sql = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = $_SESSION[user_id] AND product_id = '$product_id'";
  mysqli_query($conn, $sql);
}

// Redirect back to cart page
header('Location: cart.php');
exit;
?>
```