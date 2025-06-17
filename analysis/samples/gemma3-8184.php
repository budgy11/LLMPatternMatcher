

<?php
session_start();

// Initialize the shopping cart (array)
$cart = [];

// Helper functions:
// - add_to_cart($item_id, $name, $price, $quantity)
// - display_cart()
// - update_cart($item_id, $quantity)
// - remove_from_cart($item_id)


// ------------------ Helper Functions ------------------

/**
 * Adds an item to the shopping cart.
 *
 * @param int $item_id The ID of the item to add.
 * @param string $name The name of the item.
 * @param float $price The price of the item.
 * @param int $quantity The quantity of the item to add.
 */
function add_to_cart($item_id, $name, $price, $quantity) {
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    $cart[$item_id] = [
      'name' => $name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

/**
 * Displays the contents of the shopping cart.
 */
function display_cart() {
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $item_id => $item_details) {
    echo "<li>";
    echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " x " . $item_details['quantity'] . " = $" . ($item_details['price'] * $item_details['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate total
  $total = 0;
  foreach ($cart as $item_id => $item_details) {
    $total += ($item_details['price'] * $item_details['quantity']);
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

/**
 * Updates the quantity of an item in the shopping cart.
 *
 * @param int $item_id The ID of the item to update.
 * @param int $quantity The new quantity of the item.
 */
function update_cart($item_id, $quantity) {
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $quantity;
  }
}

/**
 * Removes an item from the shopping cart.
 *
 * @param int $item_id The ID of the item to remove.
 */
function remove_from_cart($item_id) {
  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  }
}


// ------------------ Main Script Logic ------------------

// Handle adding items to the cart
if (isset($_POST['add_to_cart'])) {
  $item_id = (int)$_POST['item_id'];
  $name = $_POST['name'];
  $price = (float)$_POST['price'];
  $quantity = (int)$_POST['quantity'];

  add_to_cart($item_id, $name, $price, $quantity);
}

// Handle updating item quantities
if (isset($_POST['update_cart'])) {
  $item_id = (int)$_POST['item_id'];
  $quantity = (int)$_POST['quantity'];
  update_cart($item_id, $quantity);
}

// Handle removing items from the cart
if (isset($_POST['remove_from_cart'])) {
  $item_id = (int)$_POST['item_id'];
  remove_from_cart($item_id);
}

// Display the shopping cart
display_cart();

// Example Product Data (You would typically get this from a database)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200.00],
  2 => ['name' => 'Mouse', 'price' => 25.00],
  3 => ['name' => 'Keyboard', 'price' => 75.00],
];

// Display product listing (for adding to cart)
echo "<h2>Available Products</h2>";
echo "<ul>";
foreach ($products as $id => $product) {
  echo "<li>";
  echo "<strong>" . $product['name'] . "</strong> - $" . $product['price'] . "<br>";
  echo "<form method='post'>";
  echo "<input type='hidden' name='item_id' value='" . $id . "'>";
  echo "<label for='quantity'>" . $product['name'] . " Quantity:</label>";
  echo "<input type='number' id='quantity' name='quantity' value='1' min='1'>";
  echo "<input type='submit' value='Add to Cart'>";
  echo "</form>";
  echo "</li>";
}
echo "</ul>";

?>
