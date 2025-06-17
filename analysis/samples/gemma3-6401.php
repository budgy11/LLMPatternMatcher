

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart total
function calculateCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// Example Usage (Simulated Cart Actions)

// Add an item to the cart
addToCart(1, "Awesome T-Shirt", 20.00, 2);
addToCart(2, "Cool Mug", 10.00, 1);

// Update quantity of item 1 to 3
updateCartQuantity(1, 3);

// Remove item 2
removeCartItem(2);

// Calculate and display the total
$total = calculateCartTotal();
echo "Cart Total: $" . number_format($total, 2) . "<br>";

// Display the current cart contents
echo "<h3>Cart Contents:</h3>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
  }
  echo "</ul>";
}

?>
