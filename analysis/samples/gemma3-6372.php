

<?php
session_start();

// ---------------------------------------------------
// Function to Add Item to Cart
// ---------------------------------------------------
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Add the product to the cart
  $_SESSION['cart'][] = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // You can optionally update the quantity if the item already exists
  updateCartQuantity($product_id, $quantity);
}

// ---------------------------------------------------
// Function to Update Cart Quantity
// ---------------------------------------------------
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use &$item to modify the original array
      if ($item['id'] == $product_id) {
        $item['quantity'] = $quantity;
        break;
      }
    }
  }
}

// ---------------------------------------------------
// Function to Get Cart Items
// ---------------------------------------------------
function getCartItems() {
  return $_SESSION['cart'] ?? []; // Returns the cart, or an empty array if it doesn't exist
}

// ---------------------------------------------------
// Function to Remove Item From Cart
// ---------------------------------------------------
function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                // Optional:  Re-index the array to avoid gaps
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
}


// ---------------------------------------------------
// Example Usage (Demonstration)
// ---------------------------------------------------

// Add some products to the cart
addToCart(1, "Shirt", 25.00, 2);
addToCart(2, "Shoes", 75.00, 1);
addToCart(1, "Shirt", 25.00, 3); // Add more of the shirt

// Display the cart items
$cart_items = getCartItems();
echo "<h2>Your Cart:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Remove an item
removeItemFromCart(2);

// Display the cart items again
$cart_items = getCartItems();
echo "<h2>Your Cart (After Removal):</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
