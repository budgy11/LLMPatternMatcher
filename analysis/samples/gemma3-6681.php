

<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists, create it if not
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use &$item for modification
      if ($item['product_id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        break;
      }
    }
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $key = array_search(
      array('product_id' => $product_id),
      $cart
    );

    if ($key !== false) {
      unset($cart[$key]);
    }
  }
}

// Function to get the cart contents
function get_cart_contents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return array(); // Return an empty array if the cart is empty
  }
}

// Example usage (Simulated product data - replace with your database or product list)

$products = array(
  1 => array('product_id' => 1, 'product_name' => 'Laptop', 'price' => 1200),
  2 => array('product_id' => 2, 'product_name' => 'Mouse', 'price' => 25),
  3 => array('product_id' => 3, 'product_name' => 'Keyboard', 'price' => 75)
);


// Simulate a user adding items to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);
add_to_cart(3, 'Keyboard', 75, 1);

// Simulate updating the quantity of the mouse
update_cart_quantity(2, 3);

// Get the cart contents
$cart_items = get_cart_contents();

// Display the cart items
echo "<h2>Cart Contents:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total_price = 0;
  foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
  }

  echo "<p><strong>Total Price: $" . number_format($total_price, 2) . "</strong></p>";
}


// Example of removing an item from the cart
// remove_from_cart(2); // Remove the mouse

// Get the cart contents after removal
// $cart_items = get_cart_contents();
// print_r($cart_items);

?>
