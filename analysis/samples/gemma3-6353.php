

<?php
session_start(); // Start the session

// This is a basic example.  A real-world implementation would:
// 1.  Fetch product data from a database or other source.
// 2.  Handle user input more robustly (e.g., validation, error handling).
// 3.  Implement more sophisticated session management.

// Sample product data (replace with your database or data source)
$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];


// Function to add a product to the cart
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

// Function to remove a product from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
      $product = $products[$product_id]; // Get product details
      $total_price = $product['price'] * $quantity;
      $total = $total + $total_price;
    }
  }
  return $total;
}



// ---  Example Usage (Simulating User Interaction) ---

// 1. User adds a T-Shirt to the cart
addToCart(1); // Adds one T-Shirt to the cart

// 2. User adds two Jeans to the cart
addToCart(2, 2);

// 3. User removes the T-Shirt from the cart
removeFromCart(1);


// 4. Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";

if (isset($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = $products[$product_id];
    echo "<li>" . $product['name'] . " x " . $quantity . " = $" . $product['price'] * $quantity . "</li>";
  }
} else {
  echo "<li>Your cart is empty.</li>";
}

echo "</ul>";

// 5. Calculate and display the total
echo "<p><strong>Total Cart Value:</strong> $" . calculateTotal() . "</p>";

?>
