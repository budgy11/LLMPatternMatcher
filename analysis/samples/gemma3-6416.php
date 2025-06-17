

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


// Example usage (simulated product data)
$products = array(
  1 => array('name' => 'Shirt', 'price' => 20),
  2 => array('name' => 'Pants', 'price' => 30),
  3 => array('name' => 'Shoes', 'price' => 50)
);


// Handle add to cart requests (simulated form submission)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  add_to_cart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
}

// Handle remove from cart requests
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  remove_from_cart($product_id);
}

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Total cart price calculation
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        $total_price += $product_details['price'] * $product_details['quantity'];
    }
}

echo "<p><strong>Total: $" . $total_price . "</strong></p>";
?>
