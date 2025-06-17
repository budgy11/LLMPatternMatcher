

<?php

// Session Start
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the product to the cart array
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


// Example Usage (Simulating user actions)

// 1. Add an item to the cart
addToCart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, Price $1200, Quantity 1
addToCart(2, "Mouse", 25, 2); // Product ID 2, Mouse, Price $25, Quantity 2

// 2. Update the quantity of an item
updateQuantity(1, 3);  // Change the quantity of Laptop to 3

// 3. Get the cart contents
$cart_contents = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart_contents);
echo "</pre>";

// 4. Calculate the total
$total = calculateTotal();
echo "<p>Total Cart Value: $" . $total . "</p>";

// 5. Remove an item from the cart
removeCartItem(2); // Remove the Mouse
?>
