

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product is already in the cart
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage (Demonstration - Replace with actual product data and form handling)
// Let's assume we have products with IDs 1, 2, and 3
// In a real application, you would retrieve this data from a database.

// Add a product to the cart
addToCart(1, 'Shirt', 20, 2);
addToCart(2, 'Shoes', 50, 1);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example: Remove an item
// removeCartItem(1);

// Example: Update quantity of product 2 to 3
// updateQuantity(2, 3);

?>
