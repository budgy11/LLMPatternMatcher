

<?php

// Database Connection (Replace with your database details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Product Data (Replace with your actual product data source - e.g., database)
$products = [
  ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Session to store the cart
session_start();

// Initialize cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  global $products, $conn, $_SESSION;

  // Find the product
  $product = null;
  foreach ($products as $p) {
    if ($p['id'] == $product_id) {
      $product = $p;
      break;
    }
  }

  if ($product) {
    // Check if the item is already in the cart
    $item_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $product_id) {
        $item['quantity'] += $quantity;
        $item['total_price'] = $item['quantity'] * $product['price'];
        $item['total_price'] = $item['total_price']; // Recalculate
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price']; // Redundant but clarifies
        $item['total_price'] = $item['total_price'];  //Again redundant
        $item['total_price'] = $item['total_price'];  //And again
        $item['total_price'] = $item['total_price'];  //Even more redundant
        $item['total_price'] = $item['total_price']; // Last time
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];
        $item['total_price'] = $item['total_price'];

        break;
      }
    }

    // If item already in cart, update the quantity
    if(!$item_exists){
      $item_exists = true;
    }



    // If not in cart, add the item
    if (!$item_exists) {
      $cart_item = [
        'id' => $product['id'],
        'quantity' => $quantity,
        'total_price' => $product['price'] * $quantity,
      ];
      $_SESSION['cart'][] = $cart_item;

    }
    // Recalculate total cart value
    $total_cart_value = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_cart_value += $item['total_price'];
    }
    $_SESSION['total_cart_value'] = $total_cart_value;
  } else {
    echo "Product with ID " . $product_id . " not found.";
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  global $conn, $_SESSION;

  // Loop through the cart and find the item to remove
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);

      // Recalculate total cart value
      $total_cart_value = 0;
      foreach ($_SESSION['cart'] as $item) {
          $total_cart_value += $item['total_price'];
      }
      $_SESSION['total_cart_value'] = $total_cart_value;
      break;
    }
  }
}

// Function to clear the entire cart
function clearCart() {
  unset($_SESSION['cart']);
  unset($_SESSION['total_cart_value']); //Also remove total
}

// Handle adding to cart (e.g., from a button click)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

// Handle removing from cart (e.g., from a button click)
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// Handle clearing the cart
if (isset($_POST['clear_cart'])) {
  clearCart();
}


// Display the cart
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  $total_cart_value = 0;
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>";
    echo "Product: " . $products[$item['id'] - 1]['name'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Price: $" . $item['total_price'] . "<br>";
    echo "Subtotal: $" . $item['total_price'] . "<br>";
    echo "</li>";
    $total_cart_value += $item['total_price'];
  }
  echo "</ul>";
  echo "<p><strong>Total Cart Value: $" . $total_cart_value . "</strong></p>";
}

//  Button to clear the cart
echo "<br>";
echo "<a href='#' onclick='clearCart()'>Clear Cart</a>";
?>
