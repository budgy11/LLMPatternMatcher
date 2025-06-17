    <button type="submit" name="update_quantity">Update Quantity</button>
  </form>

</body>
</html>


<?php
session_start();

// This is a basic example and can be expanded upon.

// Function to add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][] = [
    'id' => $item_id,
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Example Usage:

// Adding an item to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00);

// Displaying the cart contents
echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

// Example of removing an item
// You'd likely implement this using a session variable for the item ID
// to be removed.  This is just a conceptual illustration.

// if (isset($_POST['remove_item'])) {
//   $item_id_to_remove = $_POST['remove_item'];
//   // Implement logic to remove the item from the cart
// }

// End of example.  This code is illustrative and needs a real web app setup
// (HTML form, database, etc.) to function properly.

?>
