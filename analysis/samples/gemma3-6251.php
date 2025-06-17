

<?php

session_start();

// --- Initialize Cart (if it doesn't exist) ---
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Add item to cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Remove item from cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


// --- Example Usage (Simulating adding items to the cart) ---

// Add a product
addToCart(1, 'Laptop', 1200, 1);
// Add another laptop
addToCart(1, 'Laptop', 1200, 2);
// Add a phone
addToCart(2, 'Smartphone', 800, 1);

// Print the contents of the cart
echo "<h2>Your Cart:</h2>";
echo "<ul>";
if (empty($_SESSION['cart'])) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
    echo "</li>";
  }
}
echo "</ul>";

// --- Example: Remove an item ---
// removeFromCart(1);  //Remove the first laptop

// --- Example: Update quantity ---
// updateQuantity(2, 3); // Change the quantity of the smartphone to 3

?>
