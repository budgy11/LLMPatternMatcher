

<?php
session_start(); // Start the session

// Initialize the cart (if it doesn't exist)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add a new entry
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    return false; // Product not found in cart
  }

  if ($new_quantity <= 0) {
    // Remove the item if quantity is zero or negative
    unset($_SESSION['cart'][$product_id]);
    return true;
  }

  $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  return true;
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function get_cart_contents() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulating a user interaction) ---

// 1. Add an item to the cart
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);

// 2. Update the quantity of the mouse
update_cart_quantity(2, 5);

// 3. Remove a product
remove_from_cart(1);


// 4. Display the cart contents
$cart_contents = get_cart_contents();
echo "<h2>Your Cart:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}


?>
