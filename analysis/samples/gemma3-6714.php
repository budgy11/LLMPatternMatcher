

<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Example Usage (Simulating a shopping cart interaction)

// Add a product to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (!empty($_SESSION['cart'])) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) . "</p>";
} else {
  echo "<p>Your cart is empty.</p>";
}

// Remove an item from the cart
remove_from_cart(2);

// Update the quantity of an item
update_cart_quantity(1, 3);

// Display the cart contents again
echo "<h2>Shopping Cart (Updated)</h2>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) . "</p>";
} else {
  echo "<p>Your cart is empty.</p>";
}


?>
