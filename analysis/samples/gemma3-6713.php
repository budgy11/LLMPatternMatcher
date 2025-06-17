

<?php
session_start(); // Start the session

// --- Example Cart Logic ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// -----------------------------------------------------

// 1. Add Item to Cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increase the quantity if the product already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart with initial quantity of 1
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example usage:
add_to_cart(1, 'Awesome T-Shirt', 25.00, 2); // Add 2 t-shirts
add_to_cart(2, 'Cool Mug', 10.00, 1); // Add 1 mug
add_to_cart(1, 'Awesome T-Shirt', 25.00, 1); // Add 1 more t-shirt


// 2. Update Quantity
function update_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    // Handle the case where the product is not in the cart (e.g., remove it)
    // You might want to log this or display an error.
    // For example:
    // error_log("Product $product_id not in cart.");
  }
}

// Example usage:
update_quantity(1, 3); // Update quantity of Awesome T-Shirt to 3

// 3. Remove Item from Cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example usage:
remove_from_cart(2); // Remove the Cool Mug

// 4. Display Cart Contents (for debugging/preview)
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// 5. Calculate Total Cart Value (Example)
$total = 0;
foreach ($_SESSION['cart'] as $id => $item) {
    $total += $item['price'] * $item['quantity'];
}
echo "<p><strong>Total Cart Value: $" . number_format($total, 2) . "</strong></p>";


// --- End of Cart Logic ---

?>
