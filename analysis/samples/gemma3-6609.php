

<?php
session_start(); // Start the session

// Check if the session is already started
if (!isset($_SESSION['cart'])) {
  // Initialize the cart session array
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already in cart, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// Example usage -  You'd typically get this information from a form, database, etc.
// For demonstration, let's add some items:
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 1); // Add another Laptop
addToCart(3, "Keyboard", 75, 1);



// Display the contents of the cart
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_details) {
    echo "<li>";
    echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " x " . $item_details['quantity'] . " = $" . ($item_details['price'] * $item_details['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example:  Remove an item from the cart
// echo "<br>";
// echo "<a href='cart.php?action=remove&id=2'>Remove Mouse</a>";  // Example URL - needs more context
?>
