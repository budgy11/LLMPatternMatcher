

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$productId] = array(
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  }
}


// Example Usage (replace with your product data)

// Add an item to the cart
addItemToCart(1, 'Laptop', 1200, 1);
addItemToCart(2, 'Mouse', 25, 2);


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $item) {
    echo "<li>";
    echo "Product: " . $item['name'] . "<br>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Subtotal: $" . $item['price'] * $item['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Remove an item from the cart
//removeItemFromCart(2);

// Update quantity
//updateQuantity(1, 3);
?>
