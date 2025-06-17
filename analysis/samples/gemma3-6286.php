

<?php
session_start();

// --- Cart Operations ---

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][] = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use reference to modify directly
      if ($item['id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        break;
      }
    }
  }
}

// Function to remove an item from the cart
function removeItem($product_id) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Re-index the array to avoid gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        break;
      }
    }
  }
}

// Function to get the contents of the cart
function getCart() {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return [];
  }
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  $cart = getCart();

  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }

  return $total;
}


// --- Example Usage (Simulating User Interactions) ---

// 1. Add an item to the cart
addToCart(1, "Awesome T-Shirt", 20, 2);  // Product ID 1, Name "Awesome T-Shirt", Price 20, Quantity 2

// 2. Update the quantity of an item
updateQuantity(1, 3); // Increase quantity of product 1 to 3

// 3. Remove an item from the cart
// removeItem(1);

// 4. Get the cart contents
$cart_items = getCart();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// 5. Calculate the total
$total = calculateTotal();
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";

?>
