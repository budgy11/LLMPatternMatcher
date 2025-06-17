

<?php
session_start(); // Start the session

// ---------------------- Cart Logic ----------------------

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
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart total
function calculateCartTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}


// ---------------------- Example Usage (Simulating User Interaction) ----------------------

// Example: Adding an item to the cart
// addToCart(1, "T-Shirt", 20.00, 2); // Product ID 1, T-Shirt, Price $20, Quantity 2

// Example: Removing an item from the cart
// removeFromCart(1);

// Example: Updating the quantity of an item
// updateQuantity(1, 3); // Change the quantity of product 1 to 3

// ---------------------- Displaying the Cart ----------------------

// Displaying the cart contents
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
    // Add an option to remove this item (example - needs HTML for a button)
    echo "<form action='cart.php' method='post'>
              <input type='hidden' name='product_id' value='" . $product_id . "'>
              <button type='submit'>Remove</button>
            </form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total Cart Value:</strong> $" . number_format(calculateCartTotal(), 2) . "</p>";
}
?>
