

<?php
session_start();

// Function to add item to cart
function add_to_cart($product_id, $quantity, $product_name, $price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][$product_id] = [
    'name' => $product_name,
    'quantity' => $quantity,
    'price' => $price
  ];
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to display the cart contents
function display_cart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<ul>";
  $total = 0;

  foreach ($_SESSION['cart'] as $item) {
    $item_total = $item['quantity'] * $item['price'];
    $total += $item_total;
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . number_format($item['price'], 2) . " (Quantity: " . $item['quantity'] . ") ";
    echo "<form action='' method='post'>";
    echo "<label for='quantity_" . $item['name'] . "'>Quantity:</label>";
    echo "<input type='number' id='quantity_" . $item['name'] . "' value='" . $item['quantity'] . "' min='1' max='100' name='quantity_" . $item['name'] . "'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
    echo "</li>";
  }

  echo "</ul>";
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}

// Example Usage:

// 1. Add an item to the cart (simulated)
add_to_cart(1, 2, "Laptop", 1200);
add_to_cart(2, 1, "Mouse", 25);

// 2. Update quantity of a product (simulated)
update_cart_quantity(1, 3); // Increase laptop quantity to 3

// 3. Remove an item from the cart (simulated)
// remove_from_cart(2); //Remove mouse

// 4. Display the cart contents
display_cart();

//  Session Cleanup -  Important for security.  Consider a session timeout.
//session_destroy(); // Comment this out for development/debugging.
//session_unset();
?>
