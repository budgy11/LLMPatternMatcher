

<?php
session_start();

// Function to add item to cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists, if not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart array
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example usage:  Let's say we have products with IDs 1, 2, and 3
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 3); // Add more of the same item

// Function to remove item from cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example usage: Remove the mouse
removeCartItem(2);

// Function to update quantity of an item in cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example usage: Update the quantity of the laptop to 2
updateCartQuantity(1, 2);

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Cart Contents</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $item['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    // Add a link to remove the item
    echo "<a href='cart.php?remove=$product_id'>Remove</a>";
    echo "</li>";
  }
  echo "</ul>";
}

// Display the cart contents
displayCart();

// Example:  You would typically include this in an HTML form
// to handle adding items to the cart.  This is just illustrative.
?>
