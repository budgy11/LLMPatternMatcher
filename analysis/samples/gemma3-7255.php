

<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Initialize an empty array to store cart items
$cart = [];

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $price, $quantity) {
  global $cart; // Access the global $cart array

  $item = [
    'id' => $item_id,
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  $cart[] = $item; // Add the item to the cart array
  echo "<p>Item '$item_name' added to cart.</p>";
}

// Function to display the cart contents
function display_cart() {
  global $cart; // Access the global $cart array

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

  foreach ($cart as $item) {
    $total = $item['price'] * $item['quantity'];
    echo "<tr>";
    echo "<td>" . $item['name'] . "</td>";
    echo "<td>$" . $item['price'] . "</td>";
    echo "<td>" . $item['quantity'] . "</td>";
    echo "<td>$" . $total . "</td>";
    echo "</tr>";
  }

  echo "</table>";
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;

  // Iterate through the cart and remove the item based on its ID
  for ($i = 0; $i < count($cart); $i++) {
    if ($cart[$i]['id'] == $item_id) {
      unset($cart[$i]); // Remove the item
      // Optionally, you can re-index the array to avoid gaps
      // array_splice($cart, $i, 1);
      echo "<p>Item with ID '$item_id' removed from cart.</p>";
      return;
    }
  }
  echo "<p>Item with ID '$item_id' not found in cart.</p>";
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
  global $cart;

  // Iterate through the cart and update the quantity if the item ID matches
  foreach ($cart as $item) {
    if ($item['id'] == $item_id) {
      $item['quantity'] = $new_quantity;
      echo "<p>Quantity of item with ID '$item_id' updated to '$new_quantity'.</p>";
      return;
    }
  }
  echo "<p>Item with ID '$item_id' not found in cart.</p>";
}


// Handle Add to Cart Request (Simulated for demonstration)
if (isset($_POST['add_to_cart'])) {
  $item_id = $_POST['item_id'];
  $item_name = $_POST['item_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  add_to_cart($item_id, $item_name, $price, $quantity);
}


// Handle Remove from Cart Request
if (isset($_POST['remove_item'])) {
  $item_id = $_POST['item_id'];
  remove_from_cart($item_id);
}

// Handle Update Quantity Request
if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($item_id, $new_quantity);
}

?>
