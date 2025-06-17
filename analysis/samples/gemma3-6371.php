

<?php

// Start the session
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// ---  Functions for Cart Manipulation  ---

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
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

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to clear the entire cart
function clear_cart() {
  unset($_SESSION['cart']);
  $_SESSION['cart'] = array();
}

// Function to get the cart contents
function get_cart_contents() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulated Product Data) ---

// Dummy product data (replace with your actual database retrieval)
$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);

// ---  Handling Cart Actions (Simulated) ---

// Example:  Adding a product to the cart
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity']; // Get quantity from form
  add_to_cart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
}

// Example: Removing a product from the cart
if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $product_id = $_POST['product_id'];
  remove_from_cart($product_id);
}

// Example:  Updating quantity (simulated)
if(isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($product_id, $new_quantity);
}

// Example: Clearing the cart
if (isset($_POST['action']) && $_POST['action'] == 'clear_cart') {
  clear_cart();
}


// --- Displaying the Cart Contents ---

// Get the cart contents
$cart_contents = get_cart_contents();

// Output the cart contents
echo "<div class='cart'>";
echo "<h2>Shopping Cart</h2>";

if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='submit' value='Remove from Cart'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<form method='post'>";
  echo "<input type='hidden' name='action' value='clear_cart'>";
  echo "<input type='submit' value='Clear Cart'>";
  echo "</form>";
}
echo "</div>";

?>
