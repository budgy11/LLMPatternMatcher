

<?php

// Start the session (if not already started)
session_start();

// --- Cart Operations ---

// Function to add an item to the cart
function addItemToCart($product_id, $quantity = 1, $product_name = null) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If not, add the new item to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name ?? $product_id, // Use product name if available, otherwise product ID
    ];
  }
}


// Function to remove an item from the cart
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}



// --- Example Usage ---

// 1. Add an item to the cart
addItemToCart(123, 2); // Add 2 of product with ID 123
addItemToCart(456, 1, "Awesome T-Shirt"); // Add 1 of product with ID 456 and name "Awesome T-Shirt"


// 2. Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item_data['name'] . "<br>";
    echo "Quantity: " . $item_data['quantity'] . "<br>";
    echo "<a href='update_cart.php?product_id=" . $item_id . "&update_quantity='>Update Quantity</a> | ";
    echo "<a href='remove_from_cart.php?product_id=" . $item_id . "'>Remove from Cart</a>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
