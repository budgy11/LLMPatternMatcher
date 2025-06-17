

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
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


// --- Example Usage ---

// 1. Adding an item to the cart (e.g., when a user clicks "Add to Cart")
// addToCart(123, "Awesome T-Shirt", 25.00, 2); //  Product ID 123, T-Shirt, Price $25, Quantity 2

// 2. Removing an item from the cart (e.g., when a user clicks "Remove from Cart")
// removeFromCart(123);

// 3. Updating the quantity of an item in the cart
// updateQuantity(123, 3); // Change the quantity of product 123 to 3


// --- Displaying the Cart Contents ---

echo "<div class='cart-container'>";
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $product_price = $product_details['price'];
    $product_quantity = $product_details['quantity'];

    echo "<li>";
    echo "<strong>Product:</strong> " . $product_name . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_price, 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_quantity . "<br>";

    // Add a remove button (example)
    echo "<a href='#' onclick='removeFromCart(" . $product_id . ")'>Remove</a>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total:</strong> $" . number_format(calculateTotal(), 2) . "</p>"; //Calculate Total function below
}
echo "</div>";

// Function to calculate the total cart value
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        $total += $product_details['price'] * $product_details['quantity'];
    }
    return $total;
}
?>
