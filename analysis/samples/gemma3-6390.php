

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to calculate the cart total
function calculateCartTotal() {
  $total = 0;
  if (empty($_SESSION['cart'])) {
    return $total = 0;
  }
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Example Usage (Simulated Data - Replace with your actual product data) ---

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);

// Add another product
addToCart(2, "Mouse", 25, 2);

// Update the quantity of product 1
updateQuantity(1, 3);

// Get the cart items
$cart_items = getCartItems();
echo "<h2>Cart Items:</h2>";
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
$total = calculateCartTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";


// ---  Illustrative Session Deletion (Clean up after a purchase - Optional) ---
// You'd typically do this after the user completes their purchase
// session_unset($_SESSION['cart']);
// session_destroy();

?>
