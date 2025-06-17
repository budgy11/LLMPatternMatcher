

<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it as an empty array
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart array
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use &$item to modify the original array
      if ($item['product_id'] == $product_id) {
        $item['quantity'] = $quantity;
        break;
      }
    }
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['product_id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Optionally, re-index the array if needed
        // reset($_SESSION['cart']); 
        break;
      }
    }
  }
}

// Function to get the cart contents
function get_cart_contents() {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return array(); // Return an empty array if the cart is empty
  }
}

// Example usage (simulating a product being added to the cart)
// This is just an example - in a real application, you'd get product data from a database

// Add a product to the cart
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);


// Display the contents of the cart
$cart_contents = get_cart_contents();

echo "<h2>Your Shopping Cart</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $item) {
    echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example update
update_cart_quantity(1, 2); // Increase the quantity of Laptop to 2
echo "<br>Updated Cart: ";
$cart_contents = get_cart_contents();
echo "<pre>"; print_r($cart_contents); echo "</pre>";


// Example removal
remove_from_cart(2);
echo "<br>Cart after removing mouse: ";
$cart_contents = get_cart_contents();
echo "<pre>"; print_r($cart_contents); echo "</pre>";
?>
