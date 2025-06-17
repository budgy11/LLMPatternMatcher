

<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Function to Add Item to Cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If yes, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If no, add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// --- Example Usage ---

// Adding an item to the cart
addToCart(1, 'T-Shirt', 20.00, 2);
addToCart(2, 'Jeans', 50.00, 1);
addToCart(1, 'T-Shirt', 20.00, 3); // Add more of the same item


// --- Displaying the Cart ---

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $product_price = $product_details['price'];
    $product_quantity = $product_details['quantity'];

    echo "<li>" . $product_name . " - $" . $product_price . " x " . $product_quantity . " = $" . ($product_price * $product_quantity) . "</li>";
  }
  echo "</ul>";

  // Calculate total cart value
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $total += ($product_details['price'] * $product_details['quantity']);
  }

  echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";
}


// --- Optional:  Cart Clearing Function ---
function clearCart() {
    unset($_SESSION['cart']);
}
?>
