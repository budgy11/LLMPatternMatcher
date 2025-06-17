

<?php
session_start();

// Define session variables for the cart
$_SESSION['cart'] = array();
$_SESSION['cart_count'] = 0; // Keeps track of the number of items in the cart

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add a new entry
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
  // Update the cart count
  $_SESSION['cart_count'] = count($_SESSION['cart']);
}


// Example usage:  Let's say we have some products available:

// Product 1: ID = 1, Name = "Shirt", Price = 20
// Product 2: ID = 2, Name = "Pants", Price = 30
// Product 3: ID = 3, Name = "Shoes", Price = 50

// Add a shirt (product ID 1) to the cart - quantity 2
add_to_cart(1, 'Shirt', 20, 2);

// Add pants (product ID 2) to the cart - quantity 1
add_to_cart(2, 'Pants', 30, 1);

// Add shoes (product ID 3) to the cart - quantity 1
add_to_cart(3, 'Shoes', 50, 1);


// Display the contents of the cart

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - Price: $" . $product_data['price'] . ", Quantity: " . $product_data['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p>Total Items in Cart: " . $_SESSION['cart_count'] . "</p>";
}

// Example:  Remove an item (illustrative -  you'd typically do this on a delete button click)
// To remove a product, you'd unset the key in the $_SESSION['cart'] array.
// For example, to remove product with ID 2:
// unset($_SESSION['cart'][2]);
// $_SESSION['cart_count'] = count($_SESSION['cart']);

?>
