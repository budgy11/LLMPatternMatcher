

<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    // Item already in the cart, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to display the cart contents
function displayCart() {
  echo "<h2>Your Cart</h2>";

  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_details['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<form action='update_cart.php' method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='" . $product_details['quantity'] . "' min='1' max='100'>"; // example max limit
    echo "<button type='submit'>Update Quantity</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $total += $product_details['price'] * $product_details['quantity'];
  }
  echo "<p><strong>Total:</strong> $" . number_format($total, 2) . "</p>";
}

// --- Example Usage (For demonstration) ---
// Add some items to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);
addToCart(1, "T-Shirt", 20.00, 3); // Adding more of the same item

// Display the cart contents
displayCart();

// Example of removing an item
// removeCartItem(2);
// displayCart(); // Display the cart again after removing the item
?>
