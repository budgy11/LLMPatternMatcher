

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
      unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example Usage (simulating a product adding)
//  (You'd typically get this from your product display/form)

// Add a product to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "Product: " . $product_details['name'] . "<br>";
    echo "Price: $" . $product_details['price'] . "<br>";
    echo "Quantity: " . $product_details['quantity'] . "<br>";
    echo "Total for this item: $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}


// Example usage: Remove an item
// removeCartItem(2);

// Example usage: Update quantity
// updateCartItemQuantity(1, 3); // Increase the quantity of product 1 to 3

?>
