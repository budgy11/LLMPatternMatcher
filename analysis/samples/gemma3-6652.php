

<?php
session_start(); // Start the session

// --- Function to Add Items to Cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// --- Example Usage (Simulated Product Data) ---

// Sample products
$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75),
);

// --- Handle User Actions (Simulated) ---

// 1.  User Adds a Laptop
if (isset($_POST['add_laptop'])) {
  addToCart(1, $products[1]['name'], $products[1]['price']);
}

// 2. User Adds a Mouse
if (isset($_POST['add_mouse'])) {
  addToCart(2, $products[2]['name'], $products[2]['price']);
}

// 3. User Adds a Keyboard
if (isset($_POST['add_keyboard'])) {
  addToCart(3, $products[3]['name'], $products[3]['price']);
}



// --- Display Cart Contents ---

echo "<h1>Your Shopping Cart</h1>";

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<table border='1'>";
  echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];
    $total = $price * $quantity;

    echo "<tr>";
    echo "<td>" . $product_name . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total, 2) . "</td>";
    echo "</tr>";
  }
  echo "</table>";
}
?>
