

<?php
session_start();

// Function to add item to cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Function to remove item from cart
function removeCartItem($product_id) {
  unset($_SESSION['cart'][$product_id]);
}

// Function to display the cart
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<ul>";

  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'];
    echo "<br>";
    echo "<form action='update_cart.php' method='post'>";
    echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
    echo "<input type='number' id='quantity_" . $product_id . "' value='" . $item['quantity'] . "' min='1' max='100'>"; // Allow min/max quantity
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }

  echo "</ul>";

  echo "<p><a href='checkout.php'>Checkout</a></p>";  // Link to checkout
}


// Example usage (simulating adding to cart from a form)

// Let's assume we received a product ID of 123 and quantity of 2
// In a real application, this would come from an actual form submission
// For demonstration purposes, we'll add the product
// addToCart(123, 'Awesome T-Shirt', 25.00, 2);

// Simulating updates from a form (for demo purposes)
// updateQuantity(123, 3); //Update the quantity of product 123 to 3


// Displaying the cart
displayCart();

?>
