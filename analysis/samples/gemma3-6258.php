

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
      'quantity' => $quantity,
      'total' => $price * $quantity // Calculate total for the item
    );
  } else {
    // If the item already exists, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $newQuantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}


// Example usage:

// Add an item to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);

// Remove an item from the cart
//removeCartItem(2); // Uncomment to remove the mouse

// Update the quantity of an item
//updateCartQuantity(1, 3); //  Update laptop quantity to 3

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . $product_details['total'] . "<button onclick='removeCartItem(" . $product_id . ")'>Remove</button></li>";
  }
  echo "</ul>";
}

// You'll typically use this cart data in your HTML to display the cart and allow users to proceed to checkout.
// You'd also use this to calculate the total amount for the order.
?>
