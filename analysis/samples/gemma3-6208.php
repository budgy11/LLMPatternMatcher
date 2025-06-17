

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add a new entry
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Example Usage (This is just demonstration, you'd get this data from a form, API, etc.)
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);
add_to_cart(1, "Laptop", 1200, 3); // Adding more of the Laptop

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "<a href='update_cart.php?product_id=" . $product_id . "&quantity='>Update Quantity</a> | ";
    echo "<a href='remove_from_cart.php?product_id=" . $product_id . "'>Remove</a>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example of updating quantity (you'd handle this in the update_cart.php file)
//  echo "<br>Update quantity for product 1 to 5: <a href='update_cart.php?product_id=1&quantity=5'>Update</a>";

?>
