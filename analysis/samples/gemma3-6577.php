

<?php

// Start a session to store the cart data
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add a new entry to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


//  Example Usage (Demonstration - you'll integrate this into your actual application)
//  Simulating form submission with product data.  In a real application,
//  this would come from an HTML form.
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Add the item to the cart
  addToCart($product_id, "Awesome Product", 20.00, $quantity);

  //  You might want to redirect the user to a cart page or display a success message
  //  For example:
  //  header("Location: cart.php");
  //  exit();
}


// Example: Removing an item (for demonstration)
if (isset($_GET['remove_id'])) {
  $remove_id = $_GET['remove_id'];
  removeFromCart($remove_id);
  header("Location: cart.php");  // Redirect to cart.php after removal
  exit();
}



//  Displaying the Cart (This will be in your cart.php page)
//  This section just demonstrates how to display the cart contents.
?>
