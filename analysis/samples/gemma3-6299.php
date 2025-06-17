

<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
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

// Function to get the cart contents
function get_cart_contents() {
  return $_SESSION['cart'];
}

// Example Usage (Simulating a product list and adding items to the cart)

// Sample product data (replace with a database query in a real application)
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15]
];

// Add some items to the cart
add_to_cart(1, $products[1]['name'], $products[1]['price'], 2);
add_to_cart(2, $products[2]['name'], $products[2]['price']);
add_to_cart(3, $products[3]['name'], $products[3]['price'], 3);

// Get the cart contents
$cart_contents = get_cart_contents();

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
// remove_from_cart(2);

// Example of updating quantity
// update_cart_quantity(1, 5);
?>
