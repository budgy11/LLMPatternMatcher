

<?php

session_start();

// Cart data (in a real application, this would likely be stored in a database)
$cart = [];

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $item_price, $quantity = 1) {
  global $cart;

  // Check if the item is already in the cart
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    $cart[$item_id] = [
      'name' => $item_name,
      'price' => $item_price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;
  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $quantity) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $quantity;
  }
}

// Function to get the cart total
function get_cart_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Function to display the cart contents
function display_cart() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . number_format($item['price'], 2) . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Price per item: $" . number_format($item['price'], 2) . "<br>";
    echo "Subtotal: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
    echo "Remove: <a href='cart.php?action=remove&id=" . $item_id . "'>Remove</a><br>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . number_format(get_cart_total(), 2) . "</p>";
}


// Handle user actions (e.g., adding to cart, removing from cart)
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
  $item_id = $_GET['id'];
  remove_from_cart($item_id);
  // Redirect back to the cart page
  header("Location: cart.php");
  exit;
}

if (isset($_GET['update']) && $_GET['update'] == 'quantity') {
    $item_id = $_GET['id'];
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer
    update_quantity($item_id, $quantity);
    header("Location: cart.php");
    exit;
}

// Example product data (replace with your actual product data)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Shoes', 'price' => 80.00]
];



?>
