
<!-- JavaScript to handle removing items from the cart (this would be in a separate HTML file) -->
<script>
function removeItem(productId) {
    // You would likely send a request to the server to remove the item
    // from the session.  For this example, we'll just simulate it.
    alert("Removed product with ID: " + productId); //Replace with actual server-side removal
}
</script>


<?php
session_start();

// Initialize an empty cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Function to add an item to the cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Validate that quantity is a number
  if (!is_numeric($quantity) || $quantity <= 0) {
    return false; // Invalid quantity
  }

  // Add the item to the cart array
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  return true;
}

// --- Example Usage (Simulated Product Data) ---
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Shoes', 'price' => 80)
);


// --- Handling User Actions (Simulated) ---

// 1. Add a T-Shirt to the cart (quantity = 2)
if (isset($_POST['add_tshirt'])) {
  addToCart(1, 'T-Shirt', 20, 2);
  $message = "T-Shirt added to cart!";
}

// 2. Add Jeans to the cart (quantity = 1)
if (isset($_POST['add_jeans'])) {
  addToCart(2, 'Jeans', 50, 1);
  $message = "Jeans added to cart!";
}


// --- Displaying the Cart ---

echo "<!DOCTYPE html>
<html>
<head>
<title>Shopping Cart</title>
</head>
<body>";

echo "<h1>Shopping Cart</h1>";

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  // Loop through the cart and display the items
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_name = $item['name'];
    $price = $item['price'];
    $quantity = $item['quantity'];

    echo "<li>" . $product_name . " - $" . $price . " x " . $quantity . " = $" . ($price * $quantity) . "</li>";
  }
  echo "</ul>";
}

echo "<br>";

echo "<form method='post'>
        <label for='add_tshirt'>Add T-Shirt (Quantity):</label>
        <input type='number' name='add_tshirt' value='1' min='1' >
        <input type='submit' name='add_tshirt' value='Add to Cart'>
      </form>
      <br>
      <form method='post'>
        <label for='add_jeans'>Add Jeans (Quantity):</label>
        <input type='number' name='add_jeans' value='1' min='1'>
        <input type='submit' name='add_jeans' value='Add to Cart'>
      </form>";

echo "</body>
</html>";
?>
