

<?php
session_start();

// Array to store cart items
$cart = array();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart total
function get_cart_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}


// Example Usage (Simulating a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Simulate receiving product data from a form
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  // Add the item to the cart
  add_to_cart($product_id, $product_name, $price, $quantity);
}

// To update the quantity of an item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  update_cart_quantity($product_id, $new_quantity);
}

// To remove an item from the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
  $product_id = $_POST['product_id'];
  remove_from_cart($product_id);
}


// Display the cart contents
echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . get_cart_total() . "</strong></p>";
}

?>
