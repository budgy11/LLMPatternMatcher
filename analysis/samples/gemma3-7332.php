    </select>
    <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

</body>
</html>


<?php

// Assuming you have a database connection established.
// For demonstration purposes, we'll use a simple in-memory array for storage.

// In a real application, you'd connect to a database (e.g., MySQL, PostgreSQL)
// and use queries to manage products and orders.

// Sample products
$products = [
  1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
  2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
  3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Session to store the cart items
session_start();

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];

  if (isset($products[$product_id])) {
    $product = $products[$product_id];

    // If the product is already in the cart, increase the quantity
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
      if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
      } else {
        $_SESSION['cart'][$product_id] = ['quantity' => 1];
      }
    } else {
      // If the cart is empty, start with an empty array
      $_SESSION['cart'] = [$product_id => ['quantity' => 1]];
    }

    // You could also send a session flash message here to confirm the addition
    // (e.g., using a session variable)
  }
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            if (empty($_SESSION['cart'])) {
                // Empty the entire cart to avoid empty cart warnings
                $_SESSION['cart'] = [];
            }
        }
    }
}


if (isset($_POST['action']) && $_POST['action'] == 'clear_cart') {
    // Clear the entire cart
    $_SESSION['cart'] = [];
}



// Display the cart contents
echo '<h2>Shopping Cart</h2>';

if (empty($_SESSION['cart'])) {
  echo '<p>Your cart is empty.</p>';
} else {
  echo '<ul>';
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product = $products[$product_id];
    echo '<li>';
    echo "Product: " . $product['name'] . "<br>";
    echo "Price: $" . $product['price'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "<form method="post" action=\"#\">"; // Use '#' for same-page form submission
    echo "<input type=\"hidden\" name=\"product_id\" value=\"" . $product_id . "\">";
    echo "<button type=\"submit\" name=\"action\" value=\"remove_from_cart\">Remove</button>";
    echo "</li>";
  }
  echo '</ul>';
}

echo '<br>';
echo '<a href="checkout.php">Checkout</a>';
?>
