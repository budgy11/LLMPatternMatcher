

<?php
session_start();

// Function to add items to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increase quantity if product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the cart quantity
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart items
function get_cart_items() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculate_total() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


// --- Example Usage ---

// Add a product to the cart
add_to_cart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, price 1200, quantity 1
add_to_cart(2, "Mouse", 25, 2);  // Product ID 2, Mouse, price 25, quantity 2

// Display the cart items
echo "<h2>Your Shopping Cart</h2>";
if (empty(get_cart_items())) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach (get_cart_items() as $product_id => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $item['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Total for Item:</strong> $" . $item['price'] * $item['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total Cart Value:</strong> $" . calculate_total() . "</p>";
}

// Example: Update quantity
update_cart_quantity(1, 2); // Increase quantity of Laptop to 2

// Example: Remove an item
remove_from_cart(2); // Remove Mouse from cart

// Display updated cart
echo "<h2>Your Shopping Cart (Updated)</h2>";
if (empty(get_cart_items())) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach (get_cart_items() as $product_id => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $item['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Total for Item:</strong> $" . $item['price'] * $item['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total Cart Value:</strong> $" . calculate_total() . "</p>";
}
?>
