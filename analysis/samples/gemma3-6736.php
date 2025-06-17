

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
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

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage (Simulating user actions - Replace with your actual logic)

// 1. Add an item to the cart
addToCart(1, "Awesome T-Shirt", 25.00, 2);  // product_id, product_name, price, quantity
addToCart(2, "Cool Mug", 12.50, 1);
// 2. Update the quantity of an item
updateCartQuantity(1, 5); // Increase the quantity of product ID 1 to 5

// 3. Remove an item from the cart
// removeCartItem(2);


// Displaying the cart contents (for demonstration)
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}


?>
