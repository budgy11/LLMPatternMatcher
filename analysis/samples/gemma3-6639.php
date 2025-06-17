

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart with quantity 1
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Example usage:

// Add an item to the cart
add_to_cart(1, 'Laptop', 1200, 1); // Product ID 1, Laptop, Price $1200, Quantity 1
add_to_cart(2, 'Mouse', 25, 2); // Product ID 2, Mouse, Price $25, Quantity 2


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
    echo "<strong>Total for this item:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";

    // Add a button to remove this item
    echo "<a href='cart.php?remove=" . $product_id . "'>Remove</a><br>";

    echo "</li>";
  }
  echo "</ul>";
}

// Example of updating the quantity
// echo "<br>Update Quantity for Product 1: <input type='number' value='2'>";
// if (isset($_POST['update_quantity'])) {
//   $new_quantity = $_POST['update_quantity'];
//   update_quantity(1, $new_quantity);
//   echo "<p>Quantity updated to " . $new_quantity . "</p>";
// }

?>
