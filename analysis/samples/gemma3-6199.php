

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it with quantity
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name,
      'price' => $price
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example Usage:

// Add an item to the cart
addToCart(1, 2, "T-Shirt", 20);
addToCart(2, 1, "Jeans", 50);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty(getCartContents())) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach (getCartContents() as $item_id => $item) {
    echo "<li>";
    echo "Product: " . $item['name'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "Total: $" . $item['quantity'] * $item['price'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
// removeFromCart(1);

// Example of updating quantity
// updateQuantity(2, 3);
?>
