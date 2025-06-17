

<?php
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity) {
  // Create an associative array for the item
  $item = array(
    'id' => $productId,
    'name' => $productName,
    'price' => $price,
    'quantity' => $quantity
  );

  // Add the item to the cart array
  $_SESSION['cart'][] = $item;
}

// Example Usage:

// Add a product to the cart
addItemToCart(1, "Laptop", 1200, 1);

// Add another product to the cart
addItemToCart(2, "Mouse", 25, 2);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";

// Loop through the cart items
if (count($_SESSION['cart']) > 0) {
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
} else {
  echo "<li>Your cart is empty.</li>";
}

echo "</ul>";

// Example of updating the quantity of an item (optional)
// This is just an example - you'd usually handle this through a form or AJAX.
if (isset($_POST['update_quantity'])) {
  $itemId = $_POST['item_id'];
  $newQuantity = $_POST['quantity'];

  // Find the item in the cart
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $itemId) {
      $_SESSION['cart'][$key]['quantity'] = $newQuantity;
      break; // Exit the loop once the item is found and updated
    }
  }
}
?>
