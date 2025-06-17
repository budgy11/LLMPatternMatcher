
</body>
</html>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'name' => $product_name,
      'price' => $price
    );
  } else {
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
function updateCartItem($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Example Usage:

// 1. Adding items to the cart
addToCart(1, 2, "Laptop", 1200);  // Product ID 1, Quantity 2, Name "Laptop", Price 1200
addToCart(2, 1, "Mouse", 25);   // Product ID 2, Quantity 1, Name "Mouse", Price 25
addToCart(1, 1, "Laptop", 1200); // Add another laptop

// 2. Displaying the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}

// 3. Removing an item
// removeCartItem(2); // Example: Remove the mouse

// 4. Updating the quantity
// updateCartItem(1, 3); // Example: Change the laptop quantity to 3

// 5. Calculating the total cost (example)
$total = 0;
foreach ($_SESSION['cart'] as $product_id => $item) {
  $total += $item['quantity'] * $item['price'];
}

echo "<p><b>Total: $" . $total . "</b></p>";

?>
