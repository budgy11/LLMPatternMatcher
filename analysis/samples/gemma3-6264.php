

<?php
session_start();

// This is a simple example, suitable for small carts.  For production,
// you'll want a more robust solution (e.g., using a database).

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart.  If so, increment quantity.
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If the product is not in the cart, add it with quantity 1.
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
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

// Function to get the contents of the cart
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to clear the entire cart
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Example Usage (Simulated User Interaction) ---

// Add some items to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);
addToCart(1, "T-Shirt", 20.00, 1); // Add another T-Shirt

// Display the cart contents
echo "<h2>Your Shopping Cart:</h2>";
echo "<ul>";
$cartContents = getCartContents();
if (!empty($cartContents)) {
  foreach ($cartContents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
} else {
  echo "<li>Your cart is empty.</li>";
}
echo "</ul>";


// Simulate updating the quantity of a product
updateQuantity(1, 3); // Increase the quantity of T-Shirt to 3

// Display the updated cart contents
echo "<br>";
echo "<h2>Updated Cart Contents:</h2>";
echo "<ul>";
$cartContents = getCartContents();
if (!empty($cartContents)) {
    foreach ($cartContents as $product_id => $product_data) {
        echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
    }
} else {
  echo "<li>Your cart is empty.</li>";
}
echo "</ul>";

// Simulate removing an item
removeCartItem(2);

// Display the updated cart contents after removal
echo "<br>";
echo "<h2>Cart After Removing Jeans:</h2>";
echo "<ul>";
$cartContents = getCartContents();
if (!empty($cartContents)) {
    foreach ($cartContents as $product_id => $product_data) {
        echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
    }
} else {
  echo "<li>Your cart is empty.</li>";
}
echo "</ul>";

// Clear the cart
clearCart();

echo "<br>";
echo "<h2>Cart After Clearing:</h2>";
echo "<ul>";
$cartContents = getCartContents();
if (!empty($cartContents)) {
    foreach ($cartContents as $product_id => $product_data) {
        echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
    }
} else {
  echo "<li>Your cart is empty.</li>";
}
echo "</ul>";
?>
