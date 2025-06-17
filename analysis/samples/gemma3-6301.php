

<?php
session_start(); // Start the session

// --- Cart Functions ---

// Add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][] = [
    'id' => $item_id,
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Update quantity of an existing item
function updateQuantity($item_id, $quantity) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use &$item to modify the original item
      if ($item['id'] == $item_id) {
        $item['quantity'] = $quantity;
        break;
      }
    }
  }
}

// Remove an item from the cart
function removeFromCart($item_id) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    foreach ($cart as $key => $item) {
      if ($item['id'] == $item_id) {
        unset($cart[$key]); // Remove the item from the array
        break;
      }
    }
  }
}

// Get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return [];
}

// Calculate the cart total
function calculateCartTotal() {
    $total = 0;
    $cart = getCartContents();

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Example Usage (Demonstration) ---

// Example: Add an item to the cart
addToCart(1, "Awesome T-Shirt", 25.00, 2);
addToCart(2, "Cool Mug", 10.00, 1);

// Example: Update the quantity of an item
updateQuantity(1, 3); // Increase quantity of Awesome T-Shirt to 3

// Example: Remove an item from the cart
// removeFromCart(2); // Remove Cool Mug

// Get the current cart contents
$cart = getCartContents();
echo "<pre>";
print_r($cart);
echo "</pre>";

// Calculate and display the total
$total = calculateCartTotal();
echo "Total Cart Value: $" . number_format($total, 2) . "<br>";
?>
