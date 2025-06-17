  <button type="submit" name="update_quantity">Update Quantity</button>
</form>


<?php
session_start();

// Assuming this code is within a web page that can handle sessions

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}


// Example Usage (Simulated Product Information)
$product1_id = 1;
$product1_name = "T-Shirt";
$product1_price = 20.00;

$product2_id = 2;
$product2_name = "Jeans";
$product2_price = 50.00;

// Add items to the cart
addToCart($product1_id, $product1_name, $product1_price, 2); // Add 2 T-Shirts
addToCart($product2_id, $product2_name, $product2_price, 1); // Add 1 pair of Jeans


// Display the Cart Content
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_details['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . number_format($product_details['price'] * $product_details['quantity'], 2) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}


// Example: Remove a product from the cart (Optional - for completeness)
// If you want to add functionality to remove products.
// For example, you could add a button or link to remove a specific item.
// (This is not implemented in this example but is good to know)
// $product_id_to_remove = 1; // The ID of the product to remove
// if (isset($_SESSION['cart'][$product_id_to_remove])) {
//   unset($_SESSION['cart'][$product_id_to_remove]);
// }

?>
