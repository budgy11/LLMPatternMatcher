

<?php

session_start();

// Session variables for the cart
$cart = array();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already in cart, update quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  global $cart;
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  global $cart;
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}


// Example Usage (Simulating Product Data)
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Hat', 'price' => 15)
);


// Handle adding items to the cart (Example form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    add_to_cart($product_id, $product_name, $price, $quantity);
  }
}

// Handle removing items from the cart (Example form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
  }
}

// Handle updating quantity (Example form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($product_id, $new_quantity);
  }
}



// Displaying the Cart
$cart_total = 0;
echo "<div class='cart'>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];

    $subtotal = $price * $quantity;
    $cart_total += $subtotal;

    echo "<li>";
    echo "<strong>$product_name</strong> - $price x $quantity = $subtotal";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='$product_id'>";
    echo "<input type='hidden' name='product_id' value='$product_id'>";
    echo "<input type='number' name='quantity' value='$quantity'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
}
echo "</div>";
?>
