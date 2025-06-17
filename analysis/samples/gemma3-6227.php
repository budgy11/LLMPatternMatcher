
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart Example</title>
</head>
<body>

  <h2>Add Items to Cart</h2>
  <form method="post" action="">
    <label for="item_id">Item ID:</label>
    <select name="item_id" id="item_id">
      <option value="1">Product 1</option>
      <option value="2">Product 2</option>
      <option value="3">Product 3</option>
    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1"><br><br>

    <input type="submit" value="Add to Cart">
  </form>

</body>
</html>


<?php

session_start(); // Start the session

// Check if the cart is initialized.  If not, create it.
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array(); // Initialize an empty cart array
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
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

// Function to update quantity of an item in cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// --- Example usage (you would replace this with your actual product handling) ---

// Add some items to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 1); // Add another laptop (overwrites the previous one)


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "<br>";
    echo "<form action='' method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='submit' value='Remove';</form>";
  }
  echo "</ul>";
}

//Example update
//updateQuantity(1, 3);

// Example remove
// removeFromCart(2);

// Print cart total
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
echo "<p><strong>Total: $" . $total . "</strong></p>";

?>
