

<?php

session_start();

// Initialize an empty cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add a new entry to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<h2>Your Cart is Empty</h2>";
  } else {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    $total = 0;
    foreach ($_SESSION['cart'] as $item_id => $item_details) {
      $name = $item_details['name'];
      $price = $item_details['price'];
      $quantity = $item_details['quantity'];
      $total_item = $price * $quantity;
      $total += $total_item;

      echo "<tr>";
      echo "<td>" . $name . "</td>";
      echo "<td>$" . number_format($price, 2) . "</td>";
      echo "<td>" . $quantity . "</td>";
      echo "<td>$" . number_format($total_item, 2) . "</td>";
      echo "<td><a href='cart.php?action=remove&product_id=" . $product_id . "'>Remove</a></td>";
      echo "</tr>";
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
  }
}

// --- Example Usage (Illustrative - Adapt to your application's needs) ---

// Adding items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 2); // Adding another laptop

// Displaying the cart contents
displayCart();

// Removing an item
// removeCartItem(2); // Uncomment to remove the mouse
// displayCart(); // Display cart again to see the changes

// Updating the quantity of an item
// updateCartQuantity(1, 3); //Change Laptop quantity to 3
// displayCart();

?>
