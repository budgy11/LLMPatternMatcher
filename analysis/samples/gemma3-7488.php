

<?php

// Data storage (for demonstration - use a database in a real application)
$cart = [];

// Function to add an item to the cart
function add_to_cart(string $item_name, float $price, int $quantity = 1) {
  global $cart; // Access the global $cart variable

  $item = [
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  $cart[] = $item;

  echo "<p>Added '$item_name' to cart. Price: $" . $price . ", Quantity: " . $quantity . "</p>";
}

// Function to view the cart
function view_cart() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $item) {
    $total_item_price = $item['price'] * $item['quantity'];
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $total_item_price . "</li>";
  }
  echo "</ul>";
}

// Function to remove an item from the cart
function remove_from_cart(string $item_name) {
    global $cart;

    // Find the index of the item to remove
    $index = -1;
    for ($i = 0; $i < count($cart); $i++) {
        if ($cart[$i]['name'] === $item_name) {
            $index = $i;
            break;
        }
    }

    if ($index !== -1) {
        unset($cart[$index]);
        echo "<p>Removed '$item_name' from cart.</p>";
    } else {
        echo "<p>Item '$item_name' not found in cart.</p>";
    }
}

// Function to handle purchase (simulated - updates cart and clears it)
function purchase() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty. Nothing to purchase.</p>";
    return;
  }

  echo "<h2>Processing Your Purchase...</h2>";
  echo "<p>Thank you for your order!</p>";

  // Clear the cart after purchase (replace with database update in real application)
  $cart = [];

  echo "<p>Your cart has been cleared.</p>";
}

// ---------------------  Example Usage (HTML form with buttons) ---------------------
?>
