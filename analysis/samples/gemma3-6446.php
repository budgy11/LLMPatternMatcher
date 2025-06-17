

<?php
session_start(); // Start the PHP session

// Check if the 'cart' session variable exists
if (!isset($_SESSION['cart'])) {
  // If it doesn't exist, initialize an empty cart array
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($product_id, $product_name, $price, $quantity) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, create a new entry in the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example Usage (Illustrative - you'd likely get this data from a form or database)
// Let's add a product to the cart
addItemToCart(1, "T-Shirt", 20, 2);
addItemToCart(2, "Jeans", 50, 1);

// Function to display the cart contents
function displayCart() {
  echo "<h2>Your Shopping Cart</h2>";
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . "</li>";
    echo "<p>Quantity: " . $product_details['quantity'] . "</p>";

    // You might add an option to update quantity or remove item here
  }
  echo "</ul>";
}

// Display the cart contents
displayCart();

// Example: To remove an item (Not fully implemented but shows the concept)
// if (isset($_GET['remove'])) {
//   $product_id = $_GET['remove'];
//   if (isset($_SESSION['cart'][$product_id])) {
//     unset($_SESSION['cart'][$product_id]);
//   }
// }


?>
