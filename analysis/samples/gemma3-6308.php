

<?php

// Start session management (if not already started)
session_start();

// --- Cart Session Variables ---
// Use a session variable to store the cart content.
// Structure:  array('product_id' => ['name' => 'Product Name', 'price' => 10.00, 'quantity' => 1])
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $_SESSION['cart'];

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it's already in the cart, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Remove an item from the cart
function removeItemFromCart($product_id) {
  global $_SESSION['cart'];

  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  global $_SESSION['cart'];

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Calculate the total cart value
function calculateCartTotal() {
  global $_SESSION['cart'];
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}


// Clear the cart
function clearCart() {
  unset($_SESSION['cart']);
}



// --- Example Usage / Sample Code (Demonstration) ---

// 1. Add an item to the cart
addToCart(1, "Laptop", 1200.00, 1);
addToCart(2, "Mouse", 25.00, 2);

// 2. Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
    // Add a remove button (for demonstration purposes)
    echo "<a href='?action=remove&product_id=" . $product_id . "'>Remove</a><br>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total Cart Value:</strong> $" . number_format(calculateCartTotal(), 2) . "</p>";
}

// 3. Example of removing an item (using a query)
// echo "<br><a href='?action=clear_cart'>Clear Cart</a>";
// if (isset($_GET['action']) && $_GET['action'] == 'clear_cart') {
//   clearCart();
//   echo "<p>Your cart has been cleared.</p>";
// }
?>
