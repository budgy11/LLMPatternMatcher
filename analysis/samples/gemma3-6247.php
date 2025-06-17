

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
      'quantity' => $quantity,
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
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

// Function to display the cart contents
function displayCart() {
  echo "<h2>Shopping Cart</h2>";
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
      echo "<li>";
      echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
      echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
      echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
      echo "<strong>Total for this item:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
      echo "</li>";
    }
    echo "</ul>";
    // Calculate the total cart value
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        $total += ($product_details['price'] * $product_details['quantity']);
    }
    echo "<p><strong>Total Cart Value:</strong> $" . $total . "</p>";
  }
}


// Example Usage (Simulating a user adding items to the cart)

// Add item 1
addToCart(1, "Laptop", 1200, 1);

// Add item 2
addToCart(2, "Mouse", 25, 2);

// Add item 1 again - to update quantity
addToCart(1, "Laptop", 1200, 3);

// Display the cart
displayCart();

// Remove item 2 from the cart
removeFromCart(2);

// Display the cart after removal
displayCart();


//  You would typically integrate this with your product details and potentially
//  a database to store and retrieve products.  This example is for demonstration
//  purposes.
?>
