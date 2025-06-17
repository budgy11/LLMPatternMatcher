

<?php
session_start();

// Check if the cart is initialized. If not, initialize it as an empty array.
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example usage:
// Let's say we have products with IDs 1, 2, and 3.
// Let's add 2 units of product 1 and 1 unit of product 2.

addToCart(1, 'Product 1', 10.00, 2);
addToCart(2, 'Product 2', 5.00, 1);


// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example: Remove product 2
removeFromCart(2);


// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Example: Update the quantity of product 1 to 5
updateQuantity(1, 5);

// Function to display the cart contents
function displayCart() {
  echo "<h2>Shopping Cart</h2>";
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_details) {
      echo "<li>" . $item_details['name'] . " - $" . $item_details['price'] . " x " . $item_details['quantity'] . " = $" . ($item_details['price'] * $item_details['quantity']) . "</li>";
    }
    echo "</ul>";
  }
}

// Call the displayCart function to show the contents
displayCart();

//  You can add more functions here for:
//  *  Calculating the total cart value
//  *  Saving the cart to a database (for persistent carts)
//  *  Clearing the cart (e.g., on logout)

?>
