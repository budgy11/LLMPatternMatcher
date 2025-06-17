

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize an empty cart
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}


// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Returns the cart array or an empty array if it doesn't exist
}


// --- Example Usage / Cart Logic ---

// 1.  Add some items to the cart

addToCart(1, "Shirt", 25.00, 2);
addToCart(2, "Pants", 50.00, 1);
addToCart(1, "Shirt", 25.00, 3); //Add another shirt

// 2. Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
$cart = getCartContents();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate total price
  $total = 0;
  foreach ($cart as $product_id => $product_data) {
    $total += $product_data['price'] * $product_data['quantity'];
  }
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}


// 3. Example: Remove an item
//removeCartItem(2);

// 4. Example: Update Quantity
//updateCartQuantity(1, 5);
?>
