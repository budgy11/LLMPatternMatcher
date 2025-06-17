

<?php

session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it
    $_SESSION['cart'] = [];
  }

  // Add the product to the cart array
  $_SESSION['cart'][] = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}


// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}


// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Re-index the array to avoid gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true;
      }
    }
    return false; // Item not found
  }
  return false; // Cart is empty
}


// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? [];  // Return empty array if cart is not initialized
}


// Example Usage (Demonstration - This would typically come from a form submission)
// Simulate a product being added to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];

  addToCart($product_id, $quantity, $product_name, $price);
}

//Example of updating quantity:
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($product_id, $new_quantity);
}

//Example of removing an item
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeCartItem($product_id);
}



// Displaying the Cart Contents (for demonstration)
$cart_items = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}


?>
