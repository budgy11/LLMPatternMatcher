

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart with quantity 1
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'name' => $product_name,
      'price' => $price
    );
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Example usage (simulated product data - replace with your actual data source)
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Hat', 'price' => 15)
);

// ------------------  Simulate user actions  ------------------

// User adds one T-Shirt to the cart
addToCart(1, 1, 'T-Shirt', 20);

// User adds two Jeans to the cart
addToCart(2, 2, 'Jeans', 50);

// User updates the quantity of Jeans to 5
updateQuantity(2, 5);

// User removes the Hat from the cart
removeFromCart(3);

// ------------------  Display the cart contents  ------------------

echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_name = $products[$product_id]['name'];
    $price = $products[$product_id]['price'];
    $quantity = $item['quantity'];

    echo "<li>" . $quantity . " x " . $product_name . " - $" . $price . "</li>";
  }
  echo "</ul>";
}

// Display the total price
$total_price = 0;
foreach ($_SESSION['cart'] as $product_id => $item) {
    $total_price += $item['quantity'] * $item['price'];
}
echo "<p><strong>Total: $" . number_format($total_price, 2) . "</strong></p>";
?>
