

<?php
session_start();

// Simulate a product and its price (replace with your actual data source)
$productName = "Awesome Widget";
$productPrice = 25.99;

// Check if the product is already in the cart
if (isset($_SESSION['cart']) === false) {
  // Cart is empty, initialize it as an array
  $_SESSION['cart'] = [];
}

// Add the product to the cart
$_SESSION['cart'][] = $productName . " - $" . $productPrice;

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item . "</li>";
  }
  echo "</ul>";
}

//  Example: Update quantity (optional - extend this for a full shopping cart)
//  If you want to allow users to update quantities, you'd need to add
//  variables for quantity and update $_SESSION['cart'] accordingly.
//  This is a more complex addition.

// You could add a "Update" button that would run a script to
// update the quantity in the cart.


// Example of removing an item from the cart (optional)
//  Add a "Remove" button to delete items.
//  You would then need to modify the cart array to remove the item.

?>
