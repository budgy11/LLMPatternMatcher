

<?php
session_start();

// Example: Adding an item to the cart

// Assuming you have a product ID and quantity
$product_id = 123;
$quantity = 2;

// Check if the cart already exists
if (!isset($_SESSION['cart'])) {
  // If not, initialize an empty cart array
  $_SESSION['cart'] = array();
}

// Add the product to the cart
$_SESSION['cart'][$product_id] = $quantity;

// Optionally, update the quantity if the product already exists
if (isset($_SESSION['cart'][$product_id])) {
  $_SESSION['cart'][$product_id] += $quantity;
}

// Display a message to confirm the addition
echo "Item added to cart!<br>";
echo "Cart contents: ";
foreach ($_SESSION['cart'] as $id => $qty) {
    echo $id . ": " . $qty . "<br>";
}

// Example:  Removing an item from the cart

// Assuming you have a product ID to remove
$product_id_to_remove = 123;

// Check if the product exists in the cart
if (isset($_SESSION['cart'][$product_id_to_remove])) {
    // Remove the product from the cart
    unset($_SESSION['cart'][$product_id_to_remove]);
}

// Display a message to confirm the removal
echo "Item removed from cart!<br>";
echo "Cart contents after removal: ";
foreach ($_SESSION['cart'] as $id => $qty) {
    echo $id . ": " . $qty . "<br>";
}

// Example:  Clearing the entire cart
//  To clear the entire cart, you can unset the session variable
//  $_SESSION['cart'] = array();
//  echo "Cart cleared!";

?>
