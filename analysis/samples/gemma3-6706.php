

<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Example Function to Add Items to the Cart ---

function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// --- Example Usage (Simulate Adding Items) ---

// Add a product
addToCart(1, 'Laptop', 1200, 1);

// Add another item
addToCart(2, 'Mouse', 25, 2);

// Add yet another item
addToCart(1, 'Laptop', 1200, 3); //Adding more of the Laptop

// --- Display the Cart Contents ---

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
    echo "<strong>Total for Product:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// --- Example Function to Remove an Item from the Cart ---

function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example usage (Simulate removing an item)
// removeItemFromCart(2); // Remove the mouse

// --- Example Function to Update the Quantity of an Item in the Cart ---
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example usage
// updateQuantity(1, 5); //Update the quantity of the laptop to 5

// ---  Clear the Cart ---
//session_destroy(); //This will erase the entire session

?>
