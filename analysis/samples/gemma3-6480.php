

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Example Functions for Cart Operations ---

// Add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If yes, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If not, add a new item to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// Remove an item from the cart by product ID
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


// Get the contents of the cart
function get_cart_contents() {
  return $_SESSION['cart'];
}



// ---  Example Usage (Illustrative -  You'd integrate this into your application) ---

// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
add_to_cart(2, 'Jeans', 50, 1);
add_to_cart(1, 'T-Shirt', 20, 3); // Add more of the existing item

// Display the cart contents
echo "<h2>Your Cart:</h2>";
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
}


// Example of updating the cart
update_cart_quantity(1, 5);  // Increase the quantity of the T-Shirt to 5

// Display the updated cart
echo "<br>";
echo "<h2>Your Cart (Updated):</h2>";
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
}

// Example of removing an item
// remove_from_cart(2);
?>
