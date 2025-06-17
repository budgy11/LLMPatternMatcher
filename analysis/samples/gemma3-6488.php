

<?php
session_start();

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $productName = null) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = [
      'quantity' => $quantity,
      'name' => $productName ?? $productId, // Use product name if available, otherwise product ID
    ];
  }
}

// Function to remove an item from the cart
function removeCartItem($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Price:</strong>  (Assume price is stored in a database - e.g.,  $10.00)"; // Replace with your actual price retrieval
    echo "<form action='update_cart.php' method='post'>";
    echo "<input type='hidden' name='productId' value='" . $productId . "'>";
    echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'>";
    echo "<input type='submit' value='Update Quantity'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total price (replace with your actual price retrieval)
  $total = 0;
  foreach ($_SESSION['cart'] as $productId => $item) {
    $total += $item['quantity'] * 10.00;  // Example price, change to your actual price
  }

  echo "<p><strong>Total:</strong> $" . $total . "</p>";
}

// Example Usage (to add an item to the cart)
// addToCart(123, 2);  // Add product with ID 123, quantity 2

// Example Usage (to remove an item from the cart)
// removeCartItem(123);

// Example Usage (to update the quantity of an item in the cart)
// updateCartItemQuantity(123, 3);

//  To display the cart contents
displayCart();

?>
