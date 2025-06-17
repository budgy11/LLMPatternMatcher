

<?php
session_start();

// --- Cart Logic ---

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    return false; // Product not in cart
  }

  if ($quantity <= 0) {
    // Handle invalid quantity (e.g., remove the item)
    remove_from_cart($product_id);
    return false;
  }

  $_SESSION['cart'][$product_id]['quantity'] = $quantity;
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

// Function to calculate the cart total
function calculate_cart_total() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// --- Example Usage (for demonstration purposes) ---

// Simulate adding an item to the cart
add_to_cart(1, 'T-Shirt', 20.00, 2);
add_to_cart(2, 'Jeans', 50.00, 1);

// Update the quantity of a product
update_cart_quantity(1, 3); // Increase T-Shirt quantity to 5

// Get the cart contents
$cart_items = get_cart_contents();
echo "<h2>Cart Items:</h2>";
echo '<ul>';
foreach ($cart_items as $item) {
  echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}
echo '</ul>';

// Calculate and display the total
$total = calculate_cart_total();
echo "<p><strong>Total: $" . $total . "</strong></p>";


// Example: Removing an item
remove_from_cart(2);  // Remove Jeans from the cart

// Display cart contents after removal
echo "<p><strong>Cart Items After Removal:</strong></p>";
$cart_items = get_cart_contents();
echo '<ul>';
foreach ($cart_items as $item) {
  echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}
echo '</ul>';
?>
