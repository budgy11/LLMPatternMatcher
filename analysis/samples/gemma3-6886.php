

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_db";
$db_user = "your_username";
$db_password = "your_password";

// Initialize session for user information
session_start();

//  --- Function to connect to the database ---
function connect_to_db() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}


// --- Function to handle adding to cart ---
function add_to_cart($product_id, $quantity) {
  $conn = connect_to_db();

  // Check if the user is logged in
  if (!isset($_SESSION['user_id'])) {
    // User not logged in, you could redirect or handle this differently
    echo "<p>Please log in to add items to your cart.</p>";
    return false;
  }

  // Prepare the SQL query
  $sql = "INSERT INTO cart (user_id, product_id, quantity)
          VALUES (" . $_SESSION['user_id'] . ", " . $product_id . ", " . $quantity . ")";

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    echo "Error: " . $conn->error;
    return false;
  }
}


// --- Function to get the cart items ---
function get_cart_items() {
  $conn = connect_to_db();

  $sql = "SELECT p.product_name, p.product_price, c.quantity
          FROM cart c
          JOIN products p ON c.product_id = p.product_id
          WHERE c.user_id = " . $_SESSION['user_id'];

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $cart_items = [];
    while($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return []; // Return an empty array if no items in the cart
  }
}

// --- Function to calculate the total price of the cart ---
function calculate_total_price() {
  $cart_items = get_cart_items();
  $total = 0;

  foreach ($cart_items as $item) {
    $total += $item['product_price'] * $item['quantity'];
  }

  return $total;
}

// --- Function to remove an item from the cart ---
function remove_from_cart($product_id) {
    $conn = connect_to_db();

    $sql = "DELETE FROM cart WHERE user_id = " . $_SESSION['user_id'] . " AND product_id = " . $product_id;

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $conn->error;
        return false;
    }
}


// ---  Handling Purchase Request ---
if (isset($_POST['submit_purchase'])) {
  // 1. Validate the input (example - check if product_id exists)
  $product_id = $_POST['product_id'];
  // You should implement more robust validation here (e.g., check if product exists, quantity is valid, etc.)
  // For now, just assume the product exists
  $quantity = $_POST['quantity']; // Get the quantity to purchase

  // 2. Add the item to the cart (using the add_to_cart function)
  if (add_to_cart($product_id, $quantity)) {
    echo "<p>Item added to cart.  You can proceed to checkout.</p>";
    // You might want to redirect the user to the checkout page here.
  } else {
    echo "<p>Error adding item to cart.</p>";
  }
}

// --- Display Cart Items ---
$cart_items = get_cart_items();

?>
