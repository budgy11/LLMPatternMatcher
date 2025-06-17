

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Example Functions for Cart Operations ---

// Add item to cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add a new entry
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Remove item from cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update item quantity
function updateCartItemQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Get cart items
function getCartItems() {
  return $_SESSION['cart'];
}


// Calculate cart total
function calculateCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


// --- Example Usage (Demonstration) ---

// 1. Add items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 3); // Adding more of the same item

// 2. Display the cart contents
echo "<h2>Cart Items</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// 3. Display the cart total
echo "<p><strong>Cart Total:</strong> $" . calculateCartTotal() . "</p>";

// 4. Example of removing an item
// removeCartItem(2);

// 5. Example of updating quantity
// updateCartItemQuantity(1, 5);

// 6. Display the updated cart total
// echo "<p><strong>Cart Total:</strong> $" . calculateCartTotal() . "</p>";

?>
