

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
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
  unset($_SESSION['cart'][$product_id]);
}

// Function to get the cart items
function get_cart_items() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculate_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Example usage (Illustrative - Replace with your actual product details)

// Add a product to the cart
add_to_cart(1, "Awesome T-Shirt", 20.00, 2);
add_to_cart(2, "Cool Mug", 10.00, 1);


// Update the quantity of item 1 to 3
update_cart_quantity(1, 3);

// Get the cart items
$cart_items = get_cart_items();
echo "<h2>Your Cart:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// Calculate and display the total
$total = calculate_total();
echo "<p><strong>Total: $" . $total . "</strong></p>";


// Remove an item from the cart (example)
//remove_from_cart(2);
?>
