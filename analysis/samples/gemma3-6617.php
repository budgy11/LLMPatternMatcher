

<?php
session_start();

// Array to hold cart items (you'd likely get this from a database or product catalog)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (empty($_SESSION['cart'])) {
    $cart = [
      $product_id => ['name' => $product_name, 'price' => $price, 'quantity' => $quantity]
    ];
  } else {
    // Check if the product already exists in the cart
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] += $quantity;
    } else {
      $cart[$product_id] = ['name' => $product_name, 'price' => $price, 'quantity' => $quantity];
    }
  }
}

// Function to update quantity of an existing item in the cart
function update_cart_quantity($product_id, $quantity) {
    global $cart;

    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
    global $cart;

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}


// Function to get the cart contents (returns an array)
function get_cart() {
    return $cart;
}


// Add a product to the cart (example)
add_to_cart(1, "Awesome T-Shirt", 20.00, 2);
add_to_cart(2, "Cool Mug", 10.00);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example of updating the cart
update_cart_quantity(1, 5);
echo "<br>";

echo "<h2>Your Cart After Update</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
remove_from_cart(2);

// Example of getting the cart contents
echo "<br>";
echo "<h2>Cart After Removal</h2>";
echo "<ul>";
foreach ($cart as $id => $item) {
  echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
}
echo "</ul>";

//  End the session to properly save the cart data
session_write_close(); // Important:  Closes and writes the session, crucial for data persistence.
?>
