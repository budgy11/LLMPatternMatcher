

<?php

session_start(); // Start the session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Example Functions for Cart Operations ---

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Otherwise, add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    return false; // Product not found in the cart
  }

  if ($new_quantity <= 0) {
    // Remove the item if the quantity is zero or negative
    unset($_SESSION['cart'][$product_id]);
    return true;
  }

  $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  return true;
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
  return true;
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the cart total
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

// 1. Add some items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 1); // Add another laptop


// 2. Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'];
    echo " <button onclick=\"updateQuantity('" . $product_id . "', 1)">+</button>  <button onclick=\"updateQuantity('" . $product_id . "', 0)\">Remove</button>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateCartTotal() . "</strong></p>";
}



// ---  Example HTML to display the cart and update the quantity using JavaScript ---
?>
