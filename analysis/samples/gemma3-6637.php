

<?php

session_start(); // Start the session

// --- Function to add an item to the cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it as an array.
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product is not in the cart, add it with quantity 1
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// --- Example Usage ---

// Add a product to the cart
addToCart(1, "Awesome T-Shirt", 25.00, 2); // product_id, name, price, quantity

// Add another product to the cart
addToCart(2, "Cool Mug", 12.50, 1);

// --- Display the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total cart value
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $total += ($product_details['price'] * $product_details['quantity']);
  }
  echo "<p><strong>Total Cart Value:</strong> $" . $total . "</p>";
}
?>
