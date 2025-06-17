

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][$product_id] = [
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return []; // Return an empty array if cart is empty
  }
}


// --- Example Usage (Simulating a product being added to the cart) ---

//  Let's say we have a product with ID 1, name "T-Shirt", and price $20
addToCart(1, "T-Shirt", 20);

//  Now let's say the user wants to update the quantity of the T-Shirt to 3
updateCartQuantity(1, 3);

//  And they want to add one more pair of shoes (product_id 2, name "Shoes", price $50)
addToCart(2, "Shoes", 50);

//  Let's get the current cart contents
$cart = getCartContents();

// Display the cart contents (for demonstration)
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - Price: $" . $product_data['price'] . " - Quantity: " . $product_data['quantity'] . "</li>";
  }
  echo "</ul>";
}

//  Example of removing an item
// removeFromCart(1); // Remove the T-Shirt

?>
