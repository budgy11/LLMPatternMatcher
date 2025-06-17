
</body>
</html>


<?php

session_start();

// Simulate a user adding items to the cart
$cart = [];

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = [
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example usage: Add some items to the cart
addItemToCart(1, 'T-Shirt', 20.00, 2);
addItemToCart(2, 'Jeans', 50.00, 1);
addItemToCart(1, 'T-Shirt', 20.00, 1); // Add more of the T-Shirt

// --- Displaying the Cart Content ---

// Function to display the cart content
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Total for Item:</strong> $" . number_format(($item['price'] * $item['quantity']), 2) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Call the function to display the cart
displayCart();


// --- Example of Removing an Item ---
// Function to remove an item from the cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Example: Remove the 'Jeans'
// removeItemFromCart(2);

// --- Example of Updating Quantity ---
// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Example: Update the quantity of the T-Shirt to 3
// updateQuantity(1, 3);


?>
