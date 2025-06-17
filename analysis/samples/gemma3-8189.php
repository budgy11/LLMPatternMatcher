
<br>
<a href="cart.php">View Cart</a>

</body>
</html>


<?php
session_start();

// Cart data (simplified - for demonstration)
$cart = [];

// Helper functions
function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
  if (!isset($cart[$item_id])) {
    $cart[$item_id] = ['name' => $item_name, 'price' => $price, 'quantity' => $quantity];
  } else {
    $cart[$item_id]['quantity'] += $quantity;
  }
}

function get_cart_total($cart) {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

function display_cart($cart) {
  echo "<h2>Shopping Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>";
    echo "Item: " . $item['name'] . "<br>";
    echo "Price: $" . number_format($item['price'], 2) . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Total for item: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
    echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . number_format(get_cart_total($cart), 2) . "</strong></p>";
}

// ---  Example items (replace with database access in a real application) ---
$items = [
  ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];

// ---  Handling Updates (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $item_id = $_POST['item_id'];
  $quantity = $_POST['quantity'];

  if (isset($items[$item_id])) {
    $items[$item_id]['quantity'] = $quantity; // Update quantity in the item array
    // Optional:  You could also update the cart array if you are storing it in a session.
  } else {
    echo "<p>Item not found.</p>";
  }
}
?>
