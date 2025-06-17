

<?php
session_start();

// Function to add item to cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update cart quantity
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove item from cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
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
  echo "<p><strong>Total: $" . number_format($total, 2) . "</p>";
}

// Example Usage:

// Add a product to the cart
//addToCart(1, "Awesome T-Shirt", 25.00, 2);

// Update the quantity of a product
//updateCartQuantity(1, 3); // Change quantity of product ID 1 to 3

// Remove a product from the cart
//removeCartItem(1);


// Display the cart contents (This should be called on your cart.php page)
displayCart();

?>
