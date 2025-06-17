
  <a href="checkout.php">Checkout</a>  <!-- Example Checkout Page -->

</body>
</html>


<?php

session_start(); // Start the session

// Check if the session is empty, if so, initialize an empty cart
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'quantity' => $quantity,
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
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// --- Example Usage (simulating user actions) ---

// Add some items to the cart
addToCart(1, 2, 'T-Shirt', 20);
addToCart(2, 1, 'Jeans', 50);
addToCart(1, 1, 'T-Shirt', 20);  // Add another T-Shirt
updateCartQuantity(1, 3); // Update the quantity of T-Shirt to 3


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";
$cart_items = getCartContents();

if (empty($cart_items)) {
    echo "<li>Your cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Price per item: $" . $item['price'] . "<br>";
        echo "Total for this item: $" . ($item['quantity'] * $item['price']) . "<br>";
        echo "</li>";
    }
}
echo "</ul>";


// Remove an item
// removeCartItem(2); // Uncomment this line to remove the jeans from the cart
?>
