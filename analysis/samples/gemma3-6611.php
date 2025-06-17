

<?php

session_start();

// Initialize the cart (array) if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Function to Add Items to Cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// --- Example Usage (You would typically handle this from your product page or form) ---
// Example 1: Adding a single item
addToCart(1, "Laptop", 1200, 1);

// Example 2: Adding multiple items
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 3); // Add more of the Laptop

// --- Function to Display Cart Items ---
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];
    $total_item = $price * $quantity;
    $total += $total_item;

    echo "<tr>";
    echo "<td>" . $name . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total_item, 2) . "</td>";
    echo "</tr>";
  }

  echo "</table><br>";
  echo "<p><b>Total:</b> $" . number_format($total, 2) . "</p>";
}


// ---  Call the display function to show the cart contents ---
displayCart();

?>
