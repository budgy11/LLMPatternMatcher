

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Function to add an item to the cart ---
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it is, increase the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If it's not, add the product to the cart
    $_SESSION['cart'][$productId] = array(
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// --- Example Usage ---

// Add an item to the cart
addItemToCart(101, "Awesome T-Shirt", 25.00, 2); // Product ID 101, Name, Price, Quantity

// Add another item to the cart
addItemToCart(102, "Cool Mug", 12.50, 1);

// --- Function to remove an item from the cart ---
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// --- Function to update quantity of an item in cart ---
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// --- Function to display the cart contents ---
function displayCart() {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

  $total = 0;
  foreach ($_SESSION['cart'] as $productId => $product) {
    $name = $product['name'];
    $price = $product['price'];
    $quantity = $product['quantity'];
    $itemTotal = $price * $quantity;
    $total += $itemTotal;

    echo "<tr>";
    echo "<td>" . $name . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($itemTotal, 2) . "</td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<p><strong>Total: $" . number_format($total, 2) . "</p>";
}


// --- Display the cart contents ---
displayCart();
?>
