

<?php

// Start session if it doesn't exist
if (!session_id()) {
  session_start();
}

// --- Cart functions ---

// Add an item to the cart
function addToCart($product_id, $quantity = 1, $product_name = NULL) {
  // Check if the 'cart' session variable exists, initialize it if not
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product ID already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new product to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'product_name' => $product_name ?? $product_id, // Default product name if none provided
    ];
  }
}

// Remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


// Get all items in the cart
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return []; // Return an empty array if cart is empty
}


// Get the total number of items in the cart
function getCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
      $total += $item['quantity'];
    }
  }
  return $total;
}

// Clear the entire cart
function clearCart() {
  unset($_SESSION['cart']);
}

// --- Example Usage (for demonstration) ---

// Add some items to the cart
addToCart(101, 2); // Product ID 101, quantity 2
addToCart(102, 1, 'Red T-Shirt'); // Product ID 102, quantity 1, product name 'Red T-Shirt'
addToCart(103, 3);

// Display the cart contents
$cart_items = getCartItems();
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['product_name'] . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total items in cart: " . getCartTotal() . "</p>";

// Remove an item
removeFromCart(102);

// Display cart after removing an item
echo "<h2>Your Cart (After Removal)</h2>";
$cart_items = getCartItems();
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['product_name'] . "</li>";
    }
    echo "</ul>";
}

//Clear the cart
//clearCart();
?>
