

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    // Handle the case where the product isn't in the cart (e.g., product removed)
    // You might want to remove the product entirely here.
    // For simplicity, we'll just do nothing, but you should consider this.
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Example Usage (Simulated product data)
$products = array(
  1 => array('name' => 'Shirt', 'price' => 25),
  2 => array('name' => 'Pants', 'price' => 50),
  3 => array('name' => 'Shoes', 'price' => 80)
);

// --- Example Actions ---

// 1. Add a shirt to the cart
addToCart(1, $products[1]['name'], $products[1]['price']);

// 2. Add 2 pants to the cart
addToCart(2, $products[2]['name'], $products[2]['price'], 2);

// 3. Update the quantity of the shirt to 3
updateQuantity(1, 3);

// 4. Remove the pants
removeCartItem(2);

// --- Display the Cart Contents (for demonstration) ---
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}
?>
