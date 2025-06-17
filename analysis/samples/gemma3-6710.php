

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($product_id, $product_name, $price, $quantity) {
  global $session; // Use global variable for session access
  $session = $_SESSION; // Access the session variable directly
  $session['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
  // Optionally, you can sort the cart items after adding an item
  // sort($session['cart']);
}


// Example Usage:

// Simulate a product adding event (e.g., from a form submission)
//  Assuming this data comes from a form on your webpage

$product_id = 1;
$product_name = "Awesome T-Shirt";
$price = 25.00;
$quantity = 2;

// Add the item to the cart
addItemToCart($product_id, $product_name, $price, $quantity);

// Display the contents of the cart
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
// To remove an item, you can loop through the cart and find the product_id.
// Then, you would unset($_SESSION['cart'][$product_id]); // Remove by ID
// Or, you could build a remove action form.
?>
