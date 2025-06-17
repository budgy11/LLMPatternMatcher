

<?php
session_start();

// --- Cart Logic ---

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart total
function getCartTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}


// --- Example Usage (Simulating a Product List ) ---

$products = [
  ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// --- Handling User Interactions (Simulated) ---

// 1. Add to Cart (Example: Adding a T-Shirt)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
  addToCart($product_id, $products[$product_id - 1]['name'], $products[$product_id - 1]['price'], $quantity);
  echo "<p>T-Shirt added to cart.  Quantity: $quantity</p>";
}

// 2. Update Quantity (Example:  User changes quantity of T-Shirt)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($product_id, $new_quantity);
    echo "<p>Quantity of T-Shirt updated to $new_quantity</p>";
}


// 3. Remove from Cart (Example: Removing Jeans)
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeCartItem($product_id);
  echo "<p>Jeans removed from cart.</p>";
}


// --- Displaying the Cart ---

echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<form method='post'>";
    echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
    echo "<input type='number' id='quantity_" . $product_id . "' value='" . $item['quantity'] . "' min='1' name='quantity_" . $product_id . "'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total:</strong> $" . number_format(getCartTotal(), 2) . "</p>";
}
?>
