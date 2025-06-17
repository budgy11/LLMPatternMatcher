

<?php
session_start();

// Function to add item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, create an empty array
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
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

// Function to update quantity of a product in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove product from cart
function removeProduct($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to view the cart
function viewCart() {
  if (empty($_SESSION['cart'])) {
    echo "<h1>Your Cart is Empty</h1>";
  } else {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $id => $item) {
      echo "<li>";
      echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate total price
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $id => $item) {
      $totalPrice += ($item['price'] * $item['quantity']);
    }
    echo "<p><strong>Total: $" . $totalPrice . "</strong></p>";
  }
}

// Example Usage (Simulating a product being added to the cart)
// You'd typically get this data from a form or other source.

// Add a product to the cart
addToCart(1, "T-Shirt", 20.00, 2); // product_id, product_name, price, quantity
addToCart(2, "Jeans", 50.00, 1);

// Update quantity of a product
updateQuantity(1, 3); // Increase the quantity of T-Shirt to 3

// Remove a product from the cart
// removeProduct(2); // Remove Jeans from the cart

// View the cart
viewCart();

// Session Management -  Important Note:  Sessions can expire.  Make sure to handle session expiration appropriately.
// Consider using a session management library for production environments.
?>
