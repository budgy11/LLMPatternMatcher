

<?php
session_start(); // Start the session

// Function to add an item to the cart
function addItemToCart($product_id, $product_name, $price, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array if it doesn't exist
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
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

// Example Usage:  This is just for demonstration.  You'd typically get this data from a form submission.

// Add some items to the cart
addItemToCart('product1', 'Laptop', 1200, 1);
addItemToCart('product2', 'Mouse', 25, 2);
addItemToCart('product1', 'Laptop', 1200, 1); // Adding another one of the same product


// Display the cart contents
echo "<h2>Cart Items</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example of removing an item
// removeItemFromCart('product2');

// Example of updating the quantity
// updateQuantity('product1', 3);

// Display updated cart
// echo "<h2>Updated Cart Items</h2>";
// if (empty($_SESSION['cart'])) {
//   echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($_SESSION['cart'] as $product_id => $product_details) {
//     echo "<li>";
//     echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
//   }
//   echo "</ul>";
// }

?>
