

<?php
session_start();

// Function to add item to cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists
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

// Function to update quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove item from cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
      unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart contents
function get_cart_contents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return array();
}

// Example Usage:
// -----------------------------------------------------

// Add an item to the cart
add_to_cart(1, 'Laptop', 1200, 1); // Product ID 1, Laptop, Price: 1200, Quantity: 1
add_to_cart(2, 'Mouse', 25, 2);   // Product ID 2, Mouse, Price: 25, Quantity: 2
add_to_cart(1, 'Laptop', 1200, 3); // Add another laptop

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
echo "<ul>";

$cart_items = get_cart_contents();

if (empty($cart_items)) {
    echo "<li>Your cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $product_info) {
        echo "<li>" . $product_info['name'] . " - $" . $product_info['price'] . " x " . $product_info['quantity'] . " = $" . ($product_info['price'] * $product_info['quantity']) . "</li>";
    }
}

echo "</ul>";

// Example of updating quantity
update_cart_quantity(2, 5); // Update the quantity of Mouse to 5

echo "<br>";
echo "<h2>Updated Cart</h2>";
echo "<ul>";

$cart_items = get_cart_contents();

if (empty($cart_items)) {
    echo "<li>Your cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $product_info) {
        echo "<li>" . $product_info['name'] . " - $" . $product_info['price'] . " x " . $product_info['quantity'] . " = $" . ($product_info['price'] * $product_info['quantity']) . "</li>";
    }
}

echo "</ul>";
?>
