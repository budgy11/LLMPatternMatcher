

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
      'total' => $price * $quantity
    );
  } else {
    // Item already in cart - increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}


// Example usage (for demonstration)
// You would typically get this data from your database or product catalog
$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);

// Add items to the cart
add_to_cart(1, 'Laptop', $products[1]['price']);
add_to_cart(2, 'Mouse', $products[2]['price'], 2); // Add 2 mice
add_to_cart(3, 'Keyboard', $products[3]['price']);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['total'] . "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item
// remove_from_cart(2);

// Example: Update quantity
// update_quantity(1, 3); // Change the quantity of Laptop to 3

?>
